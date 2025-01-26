<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    
    public function index(Request $request) {
        $posts =\App\Models\Post::all();
	return view('posts.index', compact('posts'));
    }

    public function store(Request $request) {
	$validated = $request->validate([
	    'title' => 'required|string|max:255',
	    'content' => 'required|string',
	]);

	$post = Post::create($validated);

	return response()->json($post, 201);
    }
}
