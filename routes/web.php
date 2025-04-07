<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Models\Comment;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/posts/create', \App\Livewire\Post\CreatePost::class)->name('posts.create');
    Route::get('/posts', \App\Livewire\Post\PostList::class)->name('posts.index');
    
    // New routes for editing and deleting posts
    Route::get('/posts/{post}/edit', \App\Livewire\Post\EditPost::class)->name('posts.edit');
    Route::delete('/posts/{post}', function (Post $post) {
        if ($post->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        $post->delete();
        return redirect()->route('posts.index')->with('message', 'Post deleted successfully');
    })->name('posts.destroy');
    
    // New route for deleting comments
    Route::delete('/comments/{comment}', function (Comment $comment) {
        if ($comment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        $comment->delete();
        return back()->with('message', 'Comment deleted successfully');
    })->name('comments.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
