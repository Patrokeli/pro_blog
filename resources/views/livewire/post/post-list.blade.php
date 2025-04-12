<div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Search Bar and Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white-900">Latest Posts</h1>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" 
                       type="text" 
                       placeholder="Search posts..." 
                       class="pl-10 pr-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-white-800 text-sm font-medium">
                Home
            </a>
        </div>
    </div>

    @if($posts->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <p class="text-gray-600">No posts found matching your search.</p>
        </div>
    @else
        <div class="space-y-8">
            @foreach($posts as $post)
                <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                    <!-- Post Header (Date and Category) -->
                    <div class="px-6 pt-6">
                        <div class="flex items-center text-sm text-gray-500">
                            <span>{{ $post->created_at->format('M d, Y') }}</span>
                            <span class="mx-2">•</span>
                            <span class="font-medium text-blue-600">{{ $post->category->name ?? 'General' }}</span>
                        </div>
                    </div>

                    <!-- Post Content -->
                    <div class="px-6 pb-4">
                        <h2 class="text-xl font-bold text-gray-900 mt-2 mb-3">
                            {!! $highlight($post->title) !!}
                        </h2>
                        <p class="text-gray-600 line-clamp-3 mb-4">
                            {!! $highlight(Str::limit(strip_tags($post->content), 150)) !!}
                        </p>

                        @if($post->image_url || $post->video_url)
                            <div class="mt-4 rounded-lg overflow-hidden">
                                @if($post->image_url)
                                    <img src="{{ $post->image_url }}" 
                                         alt="Post image" 
                                         class="w-full h-48 object-cover">
                                @elseif($post->video_url)
                                    <div class="relative w-full h-48 bg-gray-100 flex items-center justify-center">
                                        <video class="absolute inset-0 w-full h-full object-cover">
                                            <source src="{{ $post->video_url }}" type="video/mp4">
                                        </video>
                                        <button class="relative z-10 bg-black bg-opacity-50 rounded-full p-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Author and Actions -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <!-- Author Info -->
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-full" 
                                     src="{{ $post->user->profile_photo_url }}" 
                                     alt="{{ $post->user->name }}">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $post->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $post->user->role ?? 'User' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Post Actions -->
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                            <div class="flex space-x-4">
                                <!-- Like Button -->
                                @livewire('post.like-button', ['post' => $post], key('like-'.$post->id))

                                <!-- Comment Button -->
                                <button wire:click="toggleComments({{ $post->id }})"
                                        class="flex items-center text-gray-500 hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                         class="h-5 w-5 mr-1" 
                                         fill="none" 
                                         viewBox="0 0 24 24" 
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <span class="text-sm">{{ $post->comments_count }}</span>
                                </button>
                            </div>

                            <!-- Edit/Delete for Author/Admin -->
                            @if(auth()->id() === $post->user_id || auth()->user()->isAdmin())
                            <div class="flex space-x-3">
                                <a href="{{ route('posts.edit', $post) }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800">
                                    Edit
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-sm text-red-600 hover:text-red-800">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Comments Section -->
                    @if($expandedPosts[$post->id] ?? false)
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        @livewire('post.comment-section', ['post' => $post], key('comments-'.$post->id))
                    </div>
                    @endif
                </article>
            @endforeach
        </div>
    @endif

    <!-- Pagination -->
    <div class="pt-6">
        {{ $posts->links() }}
    </div>
</div>
