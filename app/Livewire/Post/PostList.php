<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class PostList extends Component
{
    use WithPagination;

    public $expandedPosts = [];
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];

    public function toggleComments($postId)
    {
        $this->expandedPosts[$postId] = !($this->expandedPosts[$postId] ?? false);
    }

    // Add this method to highlight search results
    protected function highlightText($text)
    {
        if (empty($this->search)) {
            return $text;
        }

        $searchTerm = preg_quote($this->search, '/');
        return preg_replace("/(".$searchTerm.")/i", "<span class=\"bg-yellow-200\">$1</span>", $text);
    }

    

    public function render()
    {
        $posts = Post::query()
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%'.$this->search.'%')
                      ->orWhere('content', 'like', '%'.$this->search.'%')
                      ->orWhereHas('user', function($userQuery) {
                          $userQuery->where('name', 'like', '%'.$this->search.'%');
                      });
                });
            })
            ->with(['user', 'comments.user', 'likes'])
            ->withCount('comments')
            ->latest()
            ->paginate(10);

        return view('livewire.post.post-list', [
            'posts' => $posts,
            'highlight' => fn ($text) => $this->highlightText($text) // Pass the method to the view
        ]);
    }
}