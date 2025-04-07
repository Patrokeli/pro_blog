<?php

// app/Livewire/Post/CommentSection.php
namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;

class CommentSection extends Component
{
    public Post $post;

    protected $listeners = ['commentAdded' => '$refresh'];

    public function render()
    {
        return view('livewire.post.comment-section', [
            'comments' => $this->post->comments()
                ->with('user')
                ->latest()
                ->get()
        ]);
    }
}