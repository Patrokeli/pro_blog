<div class="max-w-4xl mx-auto">
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Cover Photo Section -->
    <div class="relative h-64 bg-gray-200 rounded-lg overflow-hidden">
        @if($coverPhoto)
            <img src="{{ $coverPhoto->temporaryUrl() }}" alt="New cover photo" class="w-full h-full object-cover">
        @elseif($user->cover_photo_path)
            <img src="{{ asset('storage/'.$user->cover_photo_path) }}" alt="Cover photo" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                <span class="text-gray-400">No cover photo</span>
            </div>
        @endif
        
        @if($isCurrentUser)
            <div class="absolute bottom-4 right-4">
                <label class="bg-white p-2 rounded-full shadow cursor-pointer hover:bg-gray-100 transition">
                    <input type="file" wire:model="coverPhoto" class="hidden" accept="image/*">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </label>
                @error('coverPhoto') <span class="absolute bottom-12 right-4 bg-red-500 text-white text-xs px-2 py-1 rounded">{{ $message }}</span> @enderror
                
                @if($coverPhoto)
                    <div class="absolute bottom-16 right-4 space-x-2">
                        <button wire:click="saveCoverPhoto" class="px-3 py-1 bg-green-500 text-white rounded text-sm">
                            Save
                        </button>
                        <button wire:click="$set('coverPhoto', null)" class="px-3 py-1 bg-red-500 text-white rounded text-sm">
                            Cancel
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Profile Photo Section -->
    <div class="flex flex-col md:flex-row items-start md:items-end -mt-16 px-6">
        <div class="relative">
            @if($profilePhoto)
                <img src="{{ $profilePhoto->temporaryUrl() }}" 
                     alt="New profile photo" 
                     class="h-32 w-32 rounded-full border-4 border-white object-cover shadow">
            @elseif($user->profile_photo_path)
                <img src="{{ asset('storage/'.$user->profile_photo_path) }}" 
                     alt="Profile photo" 
                     class="h-32 w-32 rounded-full border-4 border-white object-cover shadow">
            @else
                <div class="h-32 w-32 rounded-full border-4 border-white bg-gray-200 flex items-center justify-center shadow">
                    <span class="text-gray-400">Add photo</span>
                </div>
            @endif
            
            @if($isCurrentUser)
                <label class="absolute bottom-0 right-0 bg-white p-2 rounded-full shadow cursor-pointer hover:bg-gray-100 transition">
                    <input type="file" wire:model="profilePhoto" class="hidden" accept="image/*">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </label>
                @error('profilePhoto') <span class="absolute -bottom-6 right-0 bg-red-500 text-white text-xs px-2 py-1 rounded">{{ $message }}</span> @enderror
                
                @if($profilePhoto)
                    <div class="absolute -bottom-16 right-0 space-x-2">
                        <button wire:click="saveProfilePhoto" class="px-3 py-1 bg-green-500 text-white rounded text-sm">
                            Save
                        </button>
                        <button wire:click="$set('profilePhoto', null)" class="px-3 py-1 bg-red-500 text-white rounded text-sm">
                            Cancel
                        </button>
                    </div>
                @endif
            @endif
        </div>

        <!-- User Info Section -->
        <div class="md:ml-6 mt-4 md:mt-0 flex-1">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                
                @if(!$isCurrentUser)
                    <button wire:click="toggleFollow" 
                            class="px-4 py-2 rounded-full {{ $isFollowing ? 'bg-gray-200 text-gray-800' : 'bg-blue-500 text-white' }}">
                        {{ $isFollowing ? 'Following' : 'Follow' }}
                    </button>
                @endif
            </div>

            @if($isCurrentUser)
                <div class="mt-2">
                    <input wire:model="bio" 
                           wire:blur="updateBio"
                           placeholder="Add a bio..."
                           class="w-full bg-transparent border-none focus:ring-0 p-0">
                    @error('bio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            @else
                <p class="mt-2 text-gray-600">{{ $user->bio }}</p>
            @endif

            <!-- Followers/Following/Posts Count -->
            <div class="flex mt-4 space-x-6">
                <div class="text-center">
                    <span class="font-bold block">{{ $user->posts()->count() }}</span>
                    <span class="text-gray-600 text-sm">Posts</span>
                </div>
                <div class="text-center">
                    <span class="font-bold block">{{ $user->followers()->count() }}</span>
                    <span class="text-gray-600 text-sm">Followers</span>
                </div>
                <div class="text-center">
                    <span class="font-bold block">{{ $user->following()->count() }}</span>
                    <span class="text-gray-600 text-sm">Following</span>
                </div>
            </div>
        </div>
    </div>

    <!-- User Posts Section -->
    <div class="mt-8 border-t pt-6">
        <h2 class="text-xl font-semibold mb-4">Posts</h2>
        
        @if($user->posts->isEmpty())
            <p class="text-gray-500 text-center py-8">No posts yet</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($user->posts as $post)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        @if($post->image_path)
                            <img src="{{ asset('storage/'.$post->image_path) }}" 
                                 alt="Post image" 
                                 class="w-full h-48 object-cover">
                        @elseif($post->video_path)
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        @endif
                        <div class="p-4">
                            <p class="text-gray-600 text-sm">{{ $post->created_at->diffForHumans() }}</p>
                            <p class="mt-1 font-medium">{{ Str::limit($post->title, 50) }}</p>
                            <div class="flex items-center mt-2 text-sm text-gray-500">
                                <span class="flex items-center mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    {{ $post->likes()->count() }}
                                </span>
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    {{ $post->comments()->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                {{ $user->posts()->paginate(10)->links() }}
            </div>
        @endif
    </div>
</div>