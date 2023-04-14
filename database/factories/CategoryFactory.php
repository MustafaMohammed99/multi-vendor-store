<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $category = Category::inRandomOrder()->limit(1)->first('id');
        $status = ['active', 'archived'];
        $name = $this->faker->department;

        return [
            'name'        => $name,
            'slug'        => Str::slug($name),
            'parent_id'   => $category? $category->id : null,
            'description' => $this->faker->sentence(10 ,true),
            'image'  => $this->faker->imageUrl(),
            'status'      => $status[rand(0, 1)],
        ];
    }
}
