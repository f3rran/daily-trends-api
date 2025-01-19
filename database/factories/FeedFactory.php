<?php

namespace Database\Factories;

use App\Models\Feed;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedFactory extends Factory
{
    protected $model = Feed::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'source' => $this->faker->word,
            'content' => $this->faker->paragraph,
        ];
    }
}
