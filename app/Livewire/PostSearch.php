<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;

class PostSearch extends Component
{
    use WithPagination;
    
    public $search = '';
    
    public function render()
    {
        $posts = Post::when($this->search, function($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                      ->orWhere('content', 'like', '%'.$this->search.'%')
                      ->orWhereHas('user', function($q) {
                          $q->where('name', 'like', '%'.$this->search.'%');
                      });
            })
            ->latest()
            ->paginate(10);
            
        return view('livewire.post-search', ['posts' => $posts]);
    }
}