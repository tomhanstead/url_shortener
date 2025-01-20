<?php

namespace Tests\Feature;

use App\Models\UrlMapping;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlMappingControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_encodes_a_url_and_returns_a_short_url()
    {
        $response = $this->postJson('/api/encode', ['url' => 'https://example.com']);

        $response->assertStatus(201)
            ->assertJsonStructure(['original_url', 'short_url']);

        $this->assertDatabaseHas('url_mappings', ['url' => 'https://example.com']);
    }

    /** @test */
    public function it_decodes_a_short_url_to_the_original_url()
    {
        $mapping = UrlMapping::factory()->create(['url' => 'https://example.com', 'short_key' => 'abc123']);

        $response = $this->postJson('/api/decode', ['url' => config('app.url').'/abc123']);

        $response->assertStatus(200)
            ->assertJsonFragment(['original_url' => $mapping->url]);
    }

    /** @test */
    public function it_returns_an_existing_short_url_if_url_is_already_encoded()
    {
        UrlMapping::factory()->create([
            'url' => 'https://example.com',
            'short_key' => 'abc123',
        ]);

        $response = $this->postJson('/api/encode', ['url' => 'https://example.com']);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'short_url' => config('app.url').'/abc123',
            ]);
    }

    /** @test */
    public function it_returns_an_error_if_short_url_does_not_exist()
    {
        $response = $this->postJson('/api/decode', ['url' => config('app.url').'/nonexistent']);

        $response->assertStatus(404)
            ->assertJsonFragment([
                'error' => 'Short URL not found.',
            ]);
    }

    /** @test */
    public function it_validates_the_url_during_encoding()
    {
        $response = $this->postJson('/api/encode', ['url' => 'invalid-url']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['url']);
    }

    /** @test */
    public function it_validates_the_url_during_decoding()
    {
        $response = $this->postJson('/api/decode', ['url' => 'invalid-url']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['url']);
    }

    /** @test */
    public function it_handles_short_key_collisions_gracefully()
    {
        UrlMapping::factory()->create([
            'url' => 'https://example.com',
            'short_key' => 'abc123',
        ]);

        $response = $this->postJson('/api/encode', ['url' => 'https://new-url.com']);

        $response->assertStatus(201)
            ->assertJsonMissing(['short_key' => 'abc123']);
    }
}
