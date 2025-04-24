<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MainCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected array $categoryPayload = [
        'id' => 55,
        'name' => 'Test Category',
        'description' => 'Test Description',
    ];

    protected array $updatePayload = [
        'name' => 'Updated Category',
        'description' => 'Updated Description',
    ];


    /**
     * A basic feature test example.
     */


    public function testIndex()
    {
        $categories = Category::factory()->count(3)->create();

        $categoriesArray = $categories->toArray();
        $response = $this->getJson('api/categories');


        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Categories retrieved successfully',
                'data' => $categoriesArray,
                'code' => 200,
            ])->assertJsonStructure($this->JsonStructure());


        foreach ($categories as $category) {
            $this->assertDatabaseHas('categories', [
                'name' => $category->name,
                'description' => $category->description,
                'id' => $category->id,
            ]);
        }
    }


    public function testCreate()
    {
        $response = $this->postJson('api/categories', $this->categoryPayload);
        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Categories Created successfully',
                'data' => [
                    'name' => $this->categoryPayload['name'],
                    'description' => $this->categoryPayload['description'],
                ],
                'code' => 201,
            ]);

        $response->assertJsonStructure($this->JsonStructure());

        $this->assertDatabaseHas('categories', [
            'name' => $this->categoryPayload['name'],
            'description' => $this->categoryPayload['description'],
        ]);


    }


    public function testDelete()
    {

        $category = Category::factory()->create();
        $response = $this->deleteJson('api/categories/' . $category->id);
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Categories Deleted successfully',
                'data' => null,
                'code' => 200,
            ])->assertJsonStructure($this->jsonStructure());

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);


    }


    public function testUpdate()
    {

        $category = Category::factory()->create();

        $response = $this->putJson('api/categories/' . $category->id, $this->updatePayload);
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Categories Updated successfully',
                'data' => $this->updatePayload,
                'code' => 200,
            ])->assertJsonStructure($this->JsonStructure());

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $this->updatePayload['name'],
            'description' => $this->updatePayload['description'],
        ]);


    }


    public function test_create_category_validation_fails(): void
    {
        $response = $this->postJson('api/categories', []);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'errors' => [
                        'name',
                        'description',
                    ],
                ],
                'code'
            ])
            ->assertJson([
                'status' => 'error',
                'message' => 'Validation failed',
                'data' => [
                    'errors' => [
                        'name' => ['The name field is required.'],
                        'description' => ['The description field is required.'],
                    ],
                ],
                'code' => 422,
            ]);
    }


    public function testDeleteNonExistingCategory(): void
    {
        $response = $this->deleteJson('api/categories/0');
        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Resource not found',
                'data' => null,
                'code' => 404,
            ])->assertJsonStructure($this->jsonStructure());

    }


    public function testUpdateNonExistingCategory(): void
    {

        $response = $this->putJson('api/categories/0', $this->updatePayload);
        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Resource not found',
                'data' => null,
                'code' => 404,
            ])->assertJsonStructure($this->jsonStructure());

    }

    public function JsonStructure(): array
    {
        return [
            'status',
            'message',
            'data',
            'code'
        ];
    }

}
