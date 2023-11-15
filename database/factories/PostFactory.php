<?php
// database/factories/PostFactory.php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => 'Bài Viết Test ' . $this->faker->name,
            'summary' => $this->faker->unique()->safeEmail,
            'content' => bcrypt('123456'), 
            'user_id' => 1,
            'status_id' => 1,
            'created_at' => now()
        ];
    }
}

?>