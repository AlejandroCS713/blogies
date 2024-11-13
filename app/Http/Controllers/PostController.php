<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }

    public function index()
    {
        $posts = Post::where('published_at', '<=', now())
            ->get();

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create', ['post' => new Post()]);
    }

    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();


        $title = $validated['title'];
        $body = $validated['body'];


        $slug = \Str::slug($title);
        $summary = substr($body, 0, 50); // Crear un resumen con los primeros 50 caracteres del cuerpo
        $readingTime = ceil(str_word_count($body) / 200); // Calcular el tiempo de lectura (asumiendo 200 palabras por minuto)

        // Crear el post asociado al usuario autenticado
        auth()->user()->posts()->create([
            'title' => $title,
            'body' => $body,
            'slug' => $slug,
            'summary' => $summary,
            'status' => $validated['status'], // El estado proviene del formulario
            'reading_time' => $readingTime,
            'published_at' => $validated['published_at'] ?? null, // Se usa null si no se proporciona
        ]);

        // Redirigir al índice de posts con un mensaje de éxito
        return to_route('posts.index')
            ->with('status', 'Post created successfully');
    }


    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());

        return to_route('posts.show', $post)
            ->with('status', 'Post updated successfully');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return to_route('posts.index')
            ->with('status', 'Post deleted successfully');
    }

    public function userPosts()
    {
        $posts = auth()->user()->posts;

        return view('posts.index', compact('posts'));
    }
}
