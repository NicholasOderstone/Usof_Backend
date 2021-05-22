<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::find(rand(1, 10))->id,
            'title' => $this->faker->sentence(),
            'content' => $this->faker->text(rand(200, 1000)),
            'status' => $this->faker->randomElement(array('active', 'inactive'))
        ];
    }
}
