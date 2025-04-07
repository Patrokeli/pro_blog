<!-- resources/views/livewire/post/post-list.blade.php -->
<div class="max-w-4xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold">Latest Posts</h1>

    @foreach($posts as $post)
        <div class="p-6 bg-white rounded-lg shadow">
            <!-- Post header -->
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
                    <p class="text-gray-500 text-sm">By {{ $post->user->name }}</p>
                </div>
                <span class="text-gray-400 text-sm">{{ $post->created_at->diffForHumans() }}</span>
            </div>
            
            <!-- Post content -->
            <p class="mt-4 text-gray-700">{{ $post->content }}</p>
            
            <!-- Action buttons -->
            <div class="mt-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <!-- Like button -->
                    @livewire('post.like-button', ['post' => $post], key('like-'.$post->id))
                    
                    <!-- Comment toggle button -->
                    <button wire:click="toggleComments({{ $post->id }})"
                            class="flex items-center space-x-1 text-gray-500 hover:text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>{{ $post->comments_count }}</span>
                    </button>
                </div>
                
                <!-- Edit/Delete buttons (for post owner or admin) -->
                @if(auth()->id() === $post->user_id || auth()->user()->isAdmin())
                    <div class="flex space-x-2">
                        <a href="{{ route('posts.edit', $post) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                        </form>
                    </div>
                @endif
            </div>
            
            <!-- Comments section (shown when expanded) -->
            @if($expandedPosts[$post->id] ?? false)
                @livewire('post.comment-section', ['post' => $post], key('comments-'.$post->id))
            @endif
        </div>
    @endforeach

    <!-- Pagination -->
    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>