<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;

class EditPost extends Component
{
    public Post $post;
    
    #[Rule('required|min:3|max:255')]
    public string $title = '';
    
    #[Rule('required|min:10')]
    public string $content = '';

    public function mount(Post $post)
    {
        // Ensure user can only edit their own posts
        if ($post->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $this->post = $post;
        $this->title = $post->title;
        $this->content = $post->content;
    }

    public function update()
    {
        $this->validate();

        $this->post->update([
            'title' => $this->title,
            'content' => $this->content
        ]);

        session()->flash('message', 'Post updated successfully!');
        return $this->redirect(route('posts.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.post.edit-post');
    }
}