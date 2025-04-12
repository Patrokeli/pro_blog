<div> <!-- This is the required root tag -->
    @if(auth()->check() && auth()->id() != $userId)
        <button wire:click="toggleFollow"
                class="px-3 py-1 text-sm rounded-full border 
                       {{ $isFollowing ? 'bg-gray-100 text-gray-800 border-gray-300' : 'bg-blue-500 text-white border-blue-500' }}
                       hover:shadow-sm transition-all">
            {{ $isFollowing ? 'Following' : 'Follow' }}
        </button>
    @endif
</div>