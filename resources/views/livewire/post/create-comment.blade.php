<div class="mb-4">
    @if(session('comment-message'))
        <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded mb-4">
            {{ session('comment-message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="bg-white p-4 rounded shadow-sm border border-gray-200">
        <textarea
            wire:model="content"
            placeholder="Write your comment here..."
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white text-gray-800 placeholder-gray-400"
            rows="3"
        ></textarea>
        
        @error('content')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="mt-3 flex justify-end">
            <button
                type="submit"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 border border-transparent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
            >
                Post Comment
            </button>
        </div>
    </form>
</div>
