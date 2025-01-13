<?php

namespace Database\Factories;

use App\Models\UrlMapping;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UrlMapping>
 */
class UrlMappingFactory extends Factory
{
    protected $model = UrlMapping::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'url' => $this->faker->url,
            'short_key' => substr(md5($this->faker->url), 0, 6),
        ];
    }
}
