<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Rule;

class CreatePost extends Component
{
    #[Rule('required|min:3|max:255')]
    public string $title = '';

    #[Rule('required|min:10')]
    public string $content = '';

    public function save()
    {
        $this->validate();

        auth()->user()->posts()->create([
            'title' => $this->title,
            'content' => $this->content
        ]);

        $this->reset();
        session()->flash('message', 'Post created successfully!');
    }

    public function render()
    {
        return view('livewire.post.create-post');
    }
}