<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ErrorCollection;
use App\Errors\Errors;
use App\Errors\Error;
use Illuminate\Support\Arr;
use Validator;

class ProductController extends Controller
{
    
    public function index(Product $product)
    {     
        $page = isset(request('page')['number']) ? (int) request('page')['number']: 1;
        $size = isset(request('page')['size']) ? (int) request('page')['size']: 20;      
        $filter = isset(request('filter')['search']) ? request('filter')['search'] : '';
        
        $productsList = $product::when($filter !== '', function ($query, $value) use ($filter){
                            return $query->where('SKU', $filter)->orWhere('name', 'like', '%'.$filter.'%');
                        })->paginate($size, ['*'], 'page[number]', $page);     
        
        if(count($productsList->items()) == 0){                                 
            Errors::setError(new Error(Request()->fullUrl()));            
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(404);             
        }                

        return ProductCollection::make($productsList);        
    }

    public function store(Request $request)
    {             

        $validatedData = Validator::make($request->all(), [        
            'SKU' => 'required|unique:products|max:10|min:10',
            'name' => 'required|max:25',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'img' => 'nullable|url',
        ]);

        if($validatedData->fails()){      
            foreach ($validatedData->errors()->messages() as $err) {
                Errors::setError(new Error($request->fullUrl(), $err[0], '400'));                
            }                                 
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(400);
        }       

        $product = new Product();
        $product->SKU = $request->SKU;
        $product->name = $request->name;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->img = $request->img;
        $product->user_id = Auth::guard('api')->id();
        $product->save();

        return ProductResource::make($product)->response()->setStatusCode(201);
    }

    public function show($id)    
    {
        $product = Product::find($id);
        if( ! $product ){
             Errors::setError(new Error(Request()->fullUrl()));            
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(404);            
        }
        return ProductResource::make($product);        
    }

    public function update(Request $request, $id)
    {
        
        $validatedData = Validator::make($request->all(), [        
            'name' => 'required|max:25',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'img' => 'nullable|url',
        ]);            

        if($validatedData->fails()){           
            foreach ($validatedData->errors()->messages() as $err) {
                Errors::setError(new Error($request->fullUrl(), $err[0], '400'));                
            }          
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(400);                   
        } 
        
        $product = Product::find($id);
        $product->name = $request->name;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->img = $request->img;
        $product->user_id = Auth::guard('api')->id();
        $product->save();

        return ProductResource::make($product);   
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if( ! $product ){
            Errors::setError(new Error(Request()->fullUrl()));                    
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(404);
        }
        $product->delete();
        return response()->json()->setStatusCode(204);
    }
}
