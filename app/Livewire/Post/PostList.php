<?php

// app/Livewire/Post/PostList.php
namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination;

    public $expandedPosts = []; // Tracks which posts have comments expanded

    public function toggleComments($postId)
    {
        // Toggle the expanded state for this post
        $this->expandedPosts[$postId] = !($this->expandedPosts[$postId] ?? false);
    }

    public function render()
    {
        return view('livewire.post.post-list', [
            'posts' => Post::with(['user', 'comments.user', 'likes'])
                ->withCount('comments') // Efficiently count comments
                ->latest()
                ->paginate(10)
        ]);
    }
}