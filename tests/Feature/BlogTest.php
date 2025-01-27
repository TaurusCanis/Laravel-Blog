<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_blog_post_can_be_created() {
        // Define blog post data
        $data = [
	    'title' => 'My First Blog Post',
	    'content' => 'This is the content of my first blog post'
        ];

        // Send a post request to the blog post creation route
	$response = $this->post('/posts', $data);

        // Check that the post was created in the database
        $response->assertStatus(201);
        $this->assertDatabaseHas('posts', $data);
    }

    public function test_all_blog_posts_are_displayed_on_homepage() {
        $posts = \App\Models\Post::factory()->count(3)->create();
	$response = $this->get('/posts');
	$response->assertStatus(200);
	foreach ($posts as $post) {
	    $response->assertSee($post->title);
	}
    }

    public function test_new_post_must_have_title() {
        $response = $this->post('/posts', [
		'content' => 'This is the content of the blog post.',
	]);

	$response->assertSessionHasErrors('title');
    }

    public function test_new_post_must_have_content() {
	    $response = $this->post('/posts', [
		    'title' => 'A Blog Post Title'
	    ]);

	    $response->assertSessionHasErrors('content');
    }

    public function test_a_single_blog_post_can_be_viewed() {
        $post = \App\Models\Post::factory()->create([
            'title' => 'Sample Blog Post',
	    'content' => 'This is the content of the sample blog post.',
	]);
	$response = $this->get('/posts/' . $post->id);
	$response->assertStatus(200);
	$response->assertSee($post->title);
	$response->assertSee($post->content);
    }
}
