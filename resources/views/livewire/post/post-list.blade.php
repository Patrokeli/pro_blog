<div class="max-w-4xl mx-auto space-y-8 px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-4">
            Latest Posts
        </h1>
        <a href="{{ url('/dashboard') }}" class="text-blue-500 hover:text-blue-700 font-medium text-lg">
            ← Back to Dashboard
        </a>
    </div>

    @foreach($posts as $post)
        <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow hover:shadow-md transition duration-300">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $post->title }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        By <span class="font-medium">{{ $post->user->name }}</span>
                    </p>
                </div>
                <span class="text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">
                    {{ $post->created_at->diffForHumans() }}
                </span>
            </div>
            <p class="mt-4 text-gray-700 dark:text-gray-300 leading-relaxed">
                {{ $post->content }}
            </p>
        </div>
    @endforeach

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>
