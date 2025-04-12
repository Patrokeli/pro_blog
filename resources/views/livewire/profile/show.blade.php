<div class="max-w-7xl mx-auto">
    <!-- Cover Photo Section -->
    <div class="relative h-64 w-full bg-gray-200 rounded-t-lg overflow-hidden">
        @if($user->cover_photo_path)
            <img src="{{ Storage::url($user->cover_photo_path) }}" 
                 alt="Cover photo" 
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-r from-blue-500 to-purple-600"></div>
        @endif
        
        <!-- Cover Photo Upload -->
        @if(auth()->id() === $user->id)
            <div class="absolute bottom-4 right-4">
                <label class="bg-white/90 hover:bg-white text-gray-800 font-medium py-2 px-4 rounded-lg shadow-sm cursor-pointer transition">
                    <input type="file" wire:model="coverPhoto" class="hidden" accept="image/*">
                    Change Cover
                </label>
                @if($coverPhoto)
                    <button wire:click="saveCoverPhoto" 
                            class="ml-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition">
                        Save
                    </button>
                @endif
            </div>
        @endif
    </div>

    <!-- Profile Info Section -->
    <div class="relative px-6 sm:px-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-end -mt-16 sm:-mt-20">
            <!-- Profile Photo -->
            <div class="relative group">
                <img class="h-32 w-32 sm:h-40 sm:w-40 rounded-full border-4 border-white bg-white object-cover shadow-lg" 
                     src="{{ $user->profile_photo_url }}" 
                     alt="{{ $user->name }}">
                
                @if(auth()->id() === $user->id)
                    <label class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 transition cursor-pointer">
                        <input type="file" wire:model="profilePhoto" class="hidden" accept="image/*">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </label>
                    @if($profilePhoto)
                        <button wire:click="saveProfilePhoto" 
                                class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-1 px-3 rounded-full shadow-sm transition">
                            Save
                        </button>
                    @endif
                @endif
            </div>

            <!-- User Info -->
            <div class="mt-4 sm:mt-0 sm:ml-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $user->name }}</h1>
                
                <!-- Bio Section -->
                @if(auth()->id() === $user->id)
                    <div class="mt-2 flex items-center">
                        <input wire:model="bio" 
                               wire:change.debounce.500ms="updateBio"
                               placeholder="Add a bio..." 
                               class="text-white bg-transparent border-b border-transparent focus:border-gray-300 focus:outline-none placeholder-white/70">
                    </div>
                @elseif($user->bio)
                    <p class="mt-2 text-white">{{ $user->bio }}</p>
                @endif
            </div>
        </div>

        <!-- Stats Section -->
        <div class="mt-6 flex space-x-8">
            <div class="text-center">
                <span class="block text-2xl font-bold text-white">{{ $user->posts_count }}</span>
                <span class="block text-sm text-white/80">Posts</span>
            </div>
            <div class="text-center">
                <span class="block text-2xl font-bold text-white">{{ $user->followers_count }}</span>
                <span class="block text-sm text-white/80">Followers</span>
            </div>
            <div class="text-center">
                <span class="block text-2xl font-bold text-white">{{ $user->following_count }}</span>
                <span class="block text-sm text-white/80">Following</span>
            </div>
            <div class="text-center">
                <span class="block text-2xl font-bold text-white">{{ $user->likes_count }}</span>
                <span class="block text-sm text-white/80">Likes</span>
            </div>
        </div>
    </div>

    <!-- Posts Section (kept the same with white cards) -->
    <div class="mt-12 px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-white mb-8 border-b border-white/20 pb-2">Latest Posts</h2>
        
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $post)
                <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                    <!-- Post Image -->
                    @if($post->image_url)
                        <div class="h-48 overflow-hidden">
                            <img src="{{ $post->image_url }}" 
                                 alt="Post image" 
                                 class="w-full h-full object-cover">
                        </div>
                    @endif

                    <!-- Post Content -->
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <span>{{ $post->created_at->format('M d, Y') }}</span>
                            <span class="mx-2">•</span>
                            <span class="font-medium text-blue-600">{{ $post->category->name ?? 'Uncategorized' }}</span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $post->title }}</h3>
                        <p class="text-gray-600 line-clamp-3 mb-4">
                            {{ Str::limit(strip_tags($post->content), 150) }}
                        </p>
                        
                        <!-- Author -->
                        <div class="flex items-center mt-4">
                            <img class="h-10 w-10 rounded-full" 
                                 src="{{ $post->user->profile_photo_url }}" 
                                 alt="{{ $post->user->name }}">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $post->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $post->user->role ?? 'User' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Post Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        <div class="flex justify-between items-center">
                            <!-- Like Button -->
                            @livewire('post.like-button', ['post' => $post], key('like-'.$post->id))
                            
                            <!-- Comment Count -->
                            <div class="flex items-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <span class="text-sm">{{ $post->comments_count }}</span>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
</div>