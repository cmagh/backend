<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'SKU' => $this->faker->regexify('[A-Z0-9]{10}'),
            'name' => $this->faker->text(25),
            'stock' => $this->faker->numberBetween(0, 9000),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'description' => $this->faker->text(200),
            'img' => $this->faker->imageUrl(640, 480, 'food'),
            'user_id' => $this->faker->numberBetween(1, 100)            
        ];
    }
}
