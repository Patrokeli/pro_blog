<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Rule;

class CreateComment extends Component
{
    public Post $post;
    
    #[Rule('required|min:3|max:1000', message: 'Comment must be between 3 and 1000 characters')]
    public string $content = '';

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function save()
    {
        // Validate input
        $this->validate();

        // Create the comment
        $comment = $this->post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $this->content
        ]);

        // Reset the form
        $this->reset('content');

        // Dispatch event to refresh comments
        $this->dispatch('comment-created', commentId: $comment->id);
        
        // Optional: Show success message
        session()->flash('comment-message', 'Comment posted successfully!');
    }

    public function render()
    {
        return view('livewire.post.create-comment');
    }
}