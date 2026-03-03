<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    protected $model = Media::class;

public function definition(): array {
    return [
        'id' => fake()->uuid(),
        'mediable_id' => fake()->uuid(),
        'mediable_type' => 'App\Models\System',
        'collection_name' => 'system_files',
        'file_path' => 'system/default.png',
        'file_type' => 'image',
        'file_size' => 500.00,
    ];
}
}