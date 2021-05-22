<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::find(rand(1, 10))->id,
            'content' => $this->faker->text(rand(10, 100)),
            'post_id' => \App\Models\Post::find(rand(1, 10))->id
        ];
    }
}
