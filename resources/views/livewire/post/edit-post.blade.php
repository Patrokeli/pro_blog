<div class="max-w-2xl mx-auto p-4 bg-white rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Edit Post</h2>
    
    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="update">
        <!-- Title Field -->
        <div class="mb-4">
            <label for="title" class="block text-gray-700 mb-2">Title</label>
            <input wire:model="title" type="text" id="title" class="w-full px-3 py-2 border rounded text-gray-800">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Content Field -->
        <div class="mb-4">
            <label for="content" class="block text-gray-700 mb-2">Content</label>
            <textarea wire:model="content" id="content" rows="5" class="w-full px-3 py-2 border rounded text-gray-800"></textarea>
            @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Image Upload Section -->
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Post Image</label>
            @if($currentImage)
                <div class="mb-2">
                    <img src="{{ $currentImage }}" class="max-h-40 mb-2">
                    <button wire:click="removeImage" type="button" class="text-red-500 text-sm">
                        Remove Image
                    </button>
                </div>
            @endif
            <input type="file" wire:model="image" class="w-full">
            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @if($image)
                <div class="mt-2">
                    <img src="{{ $image->temporaryUrl() }}" class="max-h-40">
                </div>
            @endif
        </div>

        <!-- Video Upload Section -->
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Post Video</label>
            @if($currentVideo)
                <div class="mb-2">
                    <video controls class="max-h-40 mb-2">
                        <source src="{{ $currentVideo }}" type="video/mp4">
                    </video>
                    <button wire:click="removeVideo" type="button" class="text-red-500 text-sm">
                        Remove Video
                    </button>
                </div>
            @endif
            <input type="file" wire:model="video" class="w-full">
            @error('video') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @if($video)
                <div class="mt-2">
                    <video controls class="max-h-40">
                        <source src="{{ $video->temporaryUrl() }}" type="video/mp4">
                    </video>
                </div>
            @endif
        </div>

        <!-- Submit and Cancel Buttons -->
        <div class="flex justify-between">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Update Post
            </button>
            <a href="{{ route('posts.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Cancel
            </a>
        </div>
    </form>
</div>
