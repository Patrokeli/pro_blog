<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;

    #[Rule('required|min:3|max:255')]
    public string $title = '';

    #[Rule('required|min:10')]
    public string $content = '';

    public $image;
    public $video;

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'content' => 'required|min:10',
        'image' => 'nullable|image|max:2048', // 2MB max
        'video' => 'nullable|mimes:mp4,mov,avi|max:51200', // 50MB max
    ];

    public function save()
    {
        $this->validate();

        // Create the post
        $post = auth()->user()->posts()->create([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        // Handle image upload if provided
        if ($this->image) {
            $path = $this->image->store('posts/images', 'public');
            $post->update(['image_path' => $path]);
        }

        // Handle video upload if provided
        if ($this->video) {
            $path = $this->video->store('posts/videos', 'public');
            $post->update(['video_path' => $path]);
        }

        $this->reset();
        session()->flash('message', 'Post created successfully!');
        return redirect()->route('posts.index');
    }

    public function render()
    {
        return view('livewire.post.create-post');
    }
}
