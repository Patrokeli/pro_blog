<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EditPost extends Component
{
    use WithFileUploads;

    public Post $post;
    
    #[Rule('required|min:3|max:255')]
    public string $title = '';
    
    #[Rule('required|min:10')]
    public string $content = '';

    public $image;
    public $video;
    public $currentImage;
    public $currentVideo;

    public function mount(Post $post)
    {
        // Ensure user can only edit their own posts
        if ($post->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $this->post = $post;
        $this->title = $post->title;
        $this->content = $post->content;

        // Store current media for later reference
        $this->currentImage = $post->image_url;
        $this->currentVideo = $post->video_url;
    }

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'content' => 'required|min:10',
        'image' => 'nullable|image|max:2048',  // 2MB max for image
        'video' => 'nullable|mimes:mp4,mov,avi|max:51200',  // 50MB max for video
    ];

    public function update()
    {
        $this->validate();

        // Update post without changing existing media paths
        $this->post->update([
            'title' => $this->title,
            'content' => $this->content
        ]);

        // Handle image upload
        if ($this->image) {
            // Delete old image if exists
            if ($this->post->image_path) {
                Storage::disk('public')->delete($this->post->image_path);
            }
            $path = $this->image->store('posts/images', 'public');
            $this->post->update(['image_path' => $path]);
            $this->currentImage = $this->post->fresh()->image_url;
            $this->image = null;
        }

        // Handle video upload
        if ($this->video) {
            // Delete old video if exists
            if ($this->post->video_path) {
                Storage::disk('public')->delete($this->post->video_path);
            }
            $path = $this->video->store('posts/videos', 'public');
            $this->post->update(['video_path' => $path]);
            $this->currentVideo = $this->post->fresh()->video_url;
            $this->video = null;
        }

        session()->flash('message', 'Post updated successfully!');
        return redirect()->route('posts.index');
    }

    public function removeImage()
    {
        // Remove the image file and update the post record
        if ($this->post->image_path) {
            Storage::disk('public')->delete($this->post->image_path);
            $this->post->update(['image_path' => null]);
            $this->currentImage = null;
        }
    }

    public function removeVideo()
    {
        // Remove the video file and update the post record
        if ($this->post->video_path) {
            Storage::disk('public')->delete($this->post->video_path);
            $this->post->update(['video_path' => null]);
            $this->currentVideo = null;
        }
    }

    public function render()
    {
        return view('livewire.post.edit-post');
    }
}
