<?php

namespace Tests\Feature;

use App\Models\Collection;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

// TODO: add assertJson for all tests
class TagControllerTest extends TestCase
{
    protected $user;
    protected $collection;
    protected $tag;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->collection = Collection::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->tag = Tag::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    #[Test]
    public function it_can_create_a_new_tag_successfully()
    {
        $response = $this->actingAs($this->user)->postJson('/api/tags', [
            'title' => 'New Tagskdhsd',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    #[Test]
    public function it_fails_to_create_a_tag_with_invalid_data()
    {
        $response = $this->actingAs($this->user)->postJson('/api/tags', [
            'title' => '', // Invalid data (empty title)
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid input',
            ]);
    }

    #[Test]
    public function it_can_retrieve_all_tags()
    {
        $response = $this->actingAs($this->user)->getJson('/api/tags');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_retrieve_a_tag_by_id()
    {
        $response = $this->actingAs($this->user)->getJson("/api/tags/{$this->tag->id}");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_update_a_tag_successfully()
    {
        $response = $this->actingAs($this->user)->putJson("/api/tags/{$this->tag->id}", [
            'title' => 'Updated Tag',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function it_can_delete_a_tag_successfully()
    {
        $response = $this->actingAs($this->user)->deleteJson("/api/tags/{$this->tag->id}");

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('tags', [
            'id' => $this->tag->id,
        ]);
    }
}
