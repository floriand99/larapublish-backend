<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Article;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_can_be_created()
    {
        $user = \App\Models\User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $data = [
            'title' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet',
        ];
        $response = $this->json('post', '/api/articles?token=' . $token, $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('articles', [
            'id' => 1,
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
    }

    public function test_article_cannot_be_created_without_providing_data()
    {
        $user = \App\Models\User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->json('post', '/api/articles?token=' . $token, []);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
        ]);
    }

    public function test_article_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $user = \App\Models\User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $article = Article::make([
            'title' => 'sdsddsdsds',
            'content' => 'sdsddsdsds',
        ]);
        $article->user_id = $user->id;
        $article->reading_time = '5 minutes';
        $article->save();
        $data = [
            'title' => 'New title',
            'content' => 'New content',
        ];
        $response = $this->json('put', '/api/articles/' . $article->id . '?token=' . $token, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('articles', [
            'title' => $data['title']
        ]);
    }

}
