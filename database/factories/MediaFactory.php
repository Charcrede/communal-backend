<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['image', 'video', 'audio']);
        
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'type' => $type,
            'url' => $this->faker->imageUrl(800, 600),
            'filename' => $this->faker->word() . '.' . $this->getExtensionForType($type),
            'size' => $this->faker->numberBetween(1024, 10485760), // 1KB à 10MB
        ];
    }

    private function getExtensionForType(string $type): string
    {
        return match ($type) {
            'image' => $this->faker->randomElement(['jpg', 'png', 'gif', 'webp']),
            'video' => $this->faker->randomElement(['mp4', 'webm', 'avi', 'mov']),
            'audio' => $this->faker->randomElement(['mp3', 'wav', 'ogg', 'flac']),
        };
    }
}