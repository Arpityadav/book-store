<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $genres = [
            "Fiction",
            "Non-fiction",
            "Science fiction",
            "Fantasy",
            "Mystery",
            "Horror",
            "Romance",
            "Historical fiction",
            "Young adult",
            "Children's literature",
            "Poetry",
            "Memoir",
            "Biography",
            "Autobiography",
            "Travel writing",
            "Essay collections",
            "Self-help"
        ];

        return [
            'title' => fake()->sentence(2),
            'author' => fake()->name(),
            'genre' => $genres[rand(0,16)],
            'description' => fake()->paragraph(40),
            'isbn' => fake()->numerify('#############'),
            'image' => fake()->imageUrl(),
            'published' => fake()->date(),
            'publisher' => fake()->name()
        ];
    }
}
