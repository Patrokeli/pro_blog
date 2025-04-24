<div class="mt-4 space-y-4">
    <!-- Comment form -->
    @auth
        @livewire('post.create-comment', ['post' => $post], key('create-comment-'.$post->id))
    @else
        <div class="text-center py-4">
            <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login to post a comment</a>
        </div>
    @endauth
    
    <!-- Existing comments list -->
    ...
</div>