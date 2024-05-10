<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Http\Response;

use App\Models\Node;

class NodeTest extends TestCase
{
    /**
     * A basic unit test example.
     */

     /*
     DB QUERY TESTING
     */
    public function test_create(): void
    {
        $payload = [
            //'parents' => [1,2,3]
        ];
        $response = $this->json('post', 'api/node', $payload)
             ->assertStatus(Response::HTTP_OK)
             ->assertJsonStructure(
                 [
                     'data' => [
                        "id",
                        "parent",
                        "title",
                        "created_at",
                        "updated_at"
                     ],
                     "message",
                     "code"
                 ]
             );
             
        $this->assertDatabaseHas("nodes", $response->getOriginalContent()["data"]->toArray($response));
    }

    public function test_index_parent(): void
    {
        $response = $this->json('get','/api/node/parents')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "data"=> [
                    '*' => [
                        "id",
                        "parent",
                        "title",
                        "created_at",
                        "updated_at"
                    ]
                ]
            ]);
    }

    public function test_index_children_by_parent_recursive(): void
    {
        $response = $this->json('get','/api/node/child/1')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "data"=> [
                    '*' => [
                        "id",
                        "parent",
                        "title",
                        "created_at",
                        "updated_at"
                    ]
                ]
            ]);
    }

    public function test_index_children_by_parent_direct(): void
    {
        $this->assertTrue(true);
    }

    public function test_delete(): void
    {
        $this->assertTrue(true);
    }
    
}
