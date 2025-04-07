<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;

class LikeButton extends Component
{
    public Post $post;

    public function toggleLike()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if ($this->post->isLikedBy(auth()->user())) {
            $this->post->likes()->where('user_id', auth()->id())->delete();
        } else {
            $this->post->likes()->create(['user_id' => auth()->id()]);
        }

        $this->post->refresh();
    }

    public function render()
    {
        return view('livewire.post.like-button');
    }
}