<div class="max-w-2xl mx-auto p-6 bg-white text-gray-800 rounded-xl shadow-md border border-gray-200">
    <h2 class="text-2xl font-bold mb-6">Create New Post</h2>

    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-6 text-sm">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Title Field -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input
                wire:model="title"
                type="text"
                id="title"
                class="w-full px-4 py-2 bg-white text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                placeholder="Enter the title here..."
            >
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Content Field -->
        <div>
            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
            <textarea
                wire:model="content"
                id="content"
                rows="6"
                class="w-full px-4 py-2 bg-white text-gray-900 border border-gray-300 rounded-lg shadow-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                placeholder="Write your post content here..."
            ></textarea>
            @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Image Upload -->
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Post Image</label>
            <input type="file" wire:model="image" class="w-full">
            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @if($image)
                <div class="mt-2">
                    <img src="{{ $image->temporaryUrl() }}" class="max-h-40">
                </div>
            @endif
        </div>

        <!-- Video Upload -->
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Post Video</label>
            <input type="file" wire:model="video" class="w-full">
            @error('video') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @if($video)
                <div class="mt-2">
                    <video controls class="max-h-40">
                        <source src="{{ $video->temporaryUrl() }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            <button
                type="submit"
                class="inline-flex items-center px-6 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
                Create Post
            </button>
        </div>
    </form>
</div>
