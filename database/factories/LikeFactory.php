<?php

namespace Database\Factories;

use App\Models\Like;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Like::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $entity = rand(0,1);
        if ($entity == 1) {
            return [
                'user_id' => \App\Models\User::find(rand(1, 10))->id,
                'entity' => 'post',
                'entity_id' => \App\Models\Post::find(rand(1, 10))->id
            ];
        } else {
            return [
                'user_id' => \App\Models\User::find(rand(1, 10))->id,
                'entity' => 'comment',
                'entity_id' => \App\Models\Comment::find(rand(1, 30))->id
            ];
        }
        
    }
}
