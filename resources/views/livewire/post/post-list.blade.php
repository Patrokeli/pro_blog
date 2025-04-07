<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
    <!-- Header with Home Link -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-white">Latest Posts</h1>
        <a href="{{ route('dashboard') }}"
           class="text-white hover:underline text-sm font-medium">
            Home
        </a>
    </div>

    @foreach($posts as $post)
        <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <!-- Post Header -->
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $post->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">By {{ $post->user->name }}</p>
                </div>
                <span class="text-sm text-gray-400 whitespace-nowrap">{{ $post->created_at->diffForHumans() }}</span>
            </div>

            <!-- Post Content -->
            <p class="mt-4 text-gray-700 leading-relaxed">{{ $post->content }}</p>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-between items-center">
                <div class="flex items-center space-x-6">
                    <!-- Like Button -->
                    @livewire('post.like-button', ['post' => $post], key('like-'.$post->id))

                    <!-- Comment Toggle Button -->
                    <button wire:click="toggleComments({{ $post->id }})"
                            class="flex items-center text-gray-600 hover:text-blue-600 focus:outline-none transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 mr-1"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="text-sm">{{ $post->comments_count }}</span>
                    </button>
                </div>

                <!-- Edit/Delete Buttons -->
                @if(auth()->id() === $post->user_id || auth()->user()->isAdmin())
                    <div class="flex space-x-4">
                        <a href="{{ route('posts.edit', $post) }}"
                           class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST"
                              onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-sm text-red-600 hover:text-red-800 font-medium transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Comments Section -->
            @if($expandedPosts[$post->id] ?? false)
                <div class="mt-6 border-t pt-4">
                    @livewire('post.comment-section', ['post' => $post], key('comments-'.$post->id))
                </div>
            @endif
        </div>
    @endforeach

    <!-- Pagination -->
    <div class="pt-6">
        {{ $posts->links() }}
    </div>
</div>
