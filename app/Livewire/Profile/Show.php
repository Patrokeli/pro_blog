<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;

class Show extends Component
{
    use WithFileUploads;

    public User $user;
    public $profilePhoto;
    public $coverPhoto;
    public $bio;

    public function mount()
    {
        $this->user = auth()->user(); // Only the current logged-in user
        $this->bio = $this->user->bio;
    }

    // Save profile photo
    public function saveProfilePhoto()
    {
        $this->validate(['profilePhoto' => 'image|max:2048']);
        
        $path = $this->profilePhoto->store('profile-photos', 'public');
        $this->user->update(['profile_photo_path' => $path]);
        
        $this->profilePhoto = null;
        session()->flash('message', 'Profile photo updated!');
    }

    // Save cover photo
    public function saveCoverPhoto()
    {
        $this->validate(['coverPhoto' => 'image|max:5120']);
        
        $path = $this->coverPhoto->store('cover-photos', 'public');
        $this->user->update(['cover_photo_path' => $path]);
        
        $this->coverPhoto = null;
        session()->flash('message', 'Cover photo updated!');
    }

    // Update bio
    public function updateBio()
    {
        $this->validate(['bio' => 'nullable|string|max:255']);
        $this->user->update(['bio' => $this->bio]);
        session()->flash('message', 'Bio updated!');
    }

    public function render()
    {
        return view('livewire.profile.show', [
            'posts' => $this->user->posts()
                ->withCount(['likes', 'comments'])
                ->latest()
                ->paginate(10)
        ]);
    }
}
