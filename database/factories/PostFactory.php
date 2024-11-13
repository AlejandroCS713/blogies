<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        $body = $this->faker->paragraphs(3, true);
        $readingTime = ceil(str_word_count($body) / 200);
        return [
            'title' => $title,
            'body' => $body,
            'summary' => substr($body, 0, 50),
            'slug' => Str::slug($title),
            'status' => $this->faker->randomElement(['published', 'draft', 'archived', 'pending']),
            'reading_time' => $readingTime,
            'published_at' => random_int(0, 2)
                ? $this->faker->dateTimeBetween('-1 month', '+1 months')
                : null,
        ];
    }
}
