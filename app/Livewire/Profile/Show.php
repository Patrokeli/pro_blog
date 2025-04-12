<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Show extends Component
{
    use WithFileUploads;

    public $user;
    public $isCurrentUser;
    public $isFollowing;
    public $profilePhoto;
    public $coverPhoto;
    public $bio;
    public $showProfilePhotoModal = false;
    public $showCoverPhotoModal = false;

    public function mount($user = null)
    {
        $this->user = $user ?: Auth::user();
        $this->isCurrentUser = !$user || $user->id === Auth::id();
        $this->isFollowing = $this->isCurrentUser ? false : Auth::user()->following()->where('following_id', $this->user->id)->exists();
        $this->bio = $this->user->bio;
    }

    public function toggleFollow()
    {
        if ($this->isCurrentUser) return;

        if ($this->isFollowing) {
            Auth::user()->following()->detach($this->user->id);
        } else {
            Auth::user()->following()->attach($this->user->id);
        }

        $this->isFollowing = !$this->isFollowing;
        $this->user->refresh();
    }

    public function updateProfilePhoto()
    {
        $this->validate([
            'profilePhoto' => 'required|image|max:2048',
        ]);
        
        $this->showProfilePhotoModal = true;
    }

    public function updateCoverPhoto()
    {
        $this->validate([
            'coverPhoto' => 'required|image|max:5120',
        ]);
        
        $this->showCoverPhotoModal = true;
    }

    public function updatedProfilePhoto()
    {
        $this->validate([
            'profilePhoto' => 'image|max:2048', // 2MB max
        ]);
        
        // Temporary URL for preview
        $this->dispatch('profilePhotoUpdated');
    }

    public function updatedCoverPhoto()
    {
        $this->validate([
            'coverPhoto' => 'image|max:5120', // 5MB max
        ]);
        
        // Temporary URL for preview
        $this->dispatch('coverPhotoUpdated');
    }

    public function saveProfilePhoto()
    {
        try {
            $path = $this->profilePhoto->store('profile-photos', 'public');
            
            if ($this->user->profile_photo_path) {
                Storage::disk('public')->delete($this->user->profile_photo_path);
            }

            $this->user->update(['profile_photo_path' => $path]);

            $this->reset('profilePhoto');
            $this->user->refresh();

            session()->flash('message', 'Profile photo updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error uploading photo: ' . $e->getMessage());
        }
    }

    public function saveCoverPhoto()
    {
        try {
            $path = $this->coverPhoto->store('cover-photos', 'public');
            
            if ($this->user->cover_photo_path) {
                Storage::disk('public')->delete($this->user->cover_photo_path);
            }

            $this->user->update(['cover_photo_path' => $path]);

            $this->reset('coverPhoto');
            $this->user->refresh();

            session()->flash('message', 'Cover photo updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error uploading photo: ' . $e->getMessage());
        }
    }

    public function cancelProfilePhotoUpload()
    {
        $this->reset('profilePhoto', 'showProfilePhotoModal');
    }

    public function cancelCoverPhotoUpload()
    {
        $this->reset('coverPhoto', 'showCoverPhotoModal');
    }

    public function render()
    {
        return view('livewire.profile.show', [
            'posts' => $this->user->posts()
                ->withCount('likes', 'comments')
                ->latest()
                ->paginate(10)
        ]);
    }
}
