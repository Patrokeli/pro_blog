<div class="max-w-2xl mx-auto p-4 bg-white rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Create New Post</h2>
    
    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="save">
        <div class="mb-4">
            <label for="title" class="block text-gray-700 mb-2">Title</label>
            <input wire:model="title" type="text" id="title" class="w-full px-3 py-2 border rounded">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="content" class="block text-gray-700 mb-2">Content</label>
            <textarea wire:model="content" id="content" rows="5" class="w-full px-3 py-2 border rounded"></textarea>
            @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Create Post
        </button>
    </form>
</div>