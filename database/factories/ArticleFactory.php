<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Rubric;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        // Générer un contenu d'article plus réaliste
        $content = $this->faker->paragraphs(5, true);
        
        return [
            'title' => $this->faker->sentence(6, true),
            'content' => $content,
            'rubric_id' => Rubric::factory(),
            'media' => [], // Sera rempli dans le seeder
        ];
    }

    public function withMedia(int $count = 2): self
    {
        return $this->afterCreating(function (Article $article) use ($count) {
            $mediaIds = Media::inRandomOrder()
                ->limit($count)
                ->pluck('id')
                ->toArray();
            
            $article->update(['media' => $mediaIds]);
        });
    }
}
