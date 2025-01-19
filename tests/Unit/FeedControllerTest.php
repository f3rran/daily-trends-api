<?php

namespace Tests\Feature;

use App\Models\Feed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index
     *
     * @return void
     */
    public function test_return_all_feeds()
    {
        Feed::factory()->create([
            'title' => "Title fake",
            'source' => "Manual",
            'content' => "Contenido fake",
        ]);

        $response = $this->getJson('/api/feeds');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'Test fake',
        ]);
    }

    /**
     * Test create new Article.
     *
     * @return void
     */
    public function test_create_feed()
    {
        $data = [
            'title' => 'Title fake',
            'source' => "Manual",
            'content' => 'Contenido fake',
        ];

        $response = $this->postJson('/api/feeds', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'title' => 'Title fake',
            'content' => 'Contenido fake',
        ]);
    }

    /**
     * Test get article by id
     *
     * @return void
     */
    public function test_get_feed_by_id()
    {
        $feed = Feed::factory()->create([
            'title' => 'Title fake',
            'source' => "Manual",
            'content' => 'Contenido fake',
        ]);

        $response = $this->getJson('/api/feeds/' . $feed->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'Title fake',
            'content' => 'Contenido fake',
        ]);
    }

    /**
     * Test get article by id 404
     *
     * @return void
     */
    public function test_get_non_existing_feed()
    {
        $response = $this->getJson('/api/feeds/999');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Article not found',
        ]);
    }

    /**
     * Test update article.
     *
     * @return void
     */
    public function test_update_feed()
    {
        $feed = Feed::factory()->create([
            'title' => 'Title fake',
            'source' => "Manual",
            'content' => 'Contenido fake',
        ]);

        $updatedData = [
            'title' => 'Title fake updated',
            'source' => "Manual",
            'content' => 'Contenido fake updated',
        ];

        $response = $this->putJson('/api/feeds/' . $feed->id, $updatedData);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'Title fake updated',
            'content' => 'Contenido fake updated',
        ]);
    }

    /**
     * Test delete article.
     *
     * @return void
     */
    public function test_delete_feed()
    {
        $feed = Feed::factory()->create([
            'title' => 'Title fake',
            'content' => 'Contenido fake',
        ]);

        $response = $this->deleteJson('/api/feeds/' . $feed->id);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Article deleted',
        ]);
    }

    /**
     * Test delete article error
     *
     * @return void
     */
    public function test_delete_non_existing_feed()
    {
        $response = $this->deleteJson('/api/feeds/999');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Article not found',
        ]);
    }
}
