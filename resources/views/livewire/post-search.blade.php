<div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Search Bar and Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Latest Posts</h1>
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
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                Home
            </a>
        </div>
    </div>

    <!-- Posts List -->
    @if($posts->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <p class="text-gray-600">No posts found matching your search.</p>
        </div>
    @else
        @foreach($posts as $post)
            <article class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Post Header with Author Info and Follow Button -->
                <div class="p-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <img src="{{ $post->user->profile_photo_url }}" 
                                 alt="{{ $post->user->name }}"
                                 class="h-10 w-10 rounded-full object-cover">
                            <div>
                                <h3 class="font-medium text-gray-900">{!! highlight($post->user->name) !!}</h3>
                                <div class="flex items-center space-x-2 text-sm text-gray-500">
                                    <time datetime="{{ $post->created_at->format('Y-m-d') }}">
                                        {{ $post->created_at->format('M j, Y') }}
                                    </time>
                                    <span>•</span>
                                    <span>3 min read</span>
                                </div>
                            </div>
                        </div>
                        @livewire('follow-button', ['userId' => $post->user_id], key('follow-'.$post->id))
                    </div>
                </div>

                <!-- Post Content -->
                <div class="p-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">{!! highlight($post->title) !!}</h2>
                    <p class="text-gray-600 line-clamp-3 mb-4">
                        {!! highlight(Str::limit(strip_tags($post->content), 150)) !!}
                    </p>

                    @if($post->image_url || $post->video_url)
                    <div class="mt-3 rounded-lg overflow-hidden">
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

                <!-- Post Footer with Actions -->
                <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
                    <div class="flex justify-between items-center">
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

                    <!-- Comments Section -->
                    @if($expandedPosts[$post->id] ?? false)
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        @livewire('post.comment-section', ['post' => $post], key('comments-'.$post->id))
                    </div>
                    @endif
                </div>
            </article>
        @endforeach
    @endif

    <!-- Pagination -->
    <div class="pt-6">
        {{ $posts->links() }}
    </div>
</div>