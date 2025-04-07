<x-layouts.app :title="__('Dashboard')">
    <div class="flex flex-1 flex-col gap-6 rounded-xl px-4 py-6 md:px-8 lg:px-12">

        <!-- Welcome Section -->
        <div class="rounded-xl bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:bg-neutral-800">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">
                Welcome back, {{ auth()->user()->name }}!
            </h1>
            <p class="mt-2 text-base text-gray-600 dark:text-gray-300">
                Share your thoughts with the community or explore what others are posting.
            </p>
            
            <div class="mt-6 flex flex-wrap gap-4">
                <a href="{{ route('posts.create') }}"
                   class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Create New Post
                </a>
                <a href="{{ route('posts.index') }}"
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    View All Posts
                </a>
            </div>
        </div>

        <!-- Stats Section (moved up) -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Total Posts -->
            <div class="rounded-xl bg-white p-5 shadow-sm dark:bg-neutral-800">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Posts</h3>
                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ auth()->user()->posts()->count() }}
                </p>
            </div>

            <!-- Latest Post -->
            <div class="rounded-xl bg-white p-5 shadow-sm dark:bg-neutral-800">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Latest Post</h3>
                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                    @if(auth()->user()->posts()->exists())
                        {{ auth()->user()->posts()->latest()->first()->created_at->diffForHumans() }}
                    @else
                        Never
                    @endif
                </p>
            </div>

            <!-- Modern Status Badge -->
            <div class="rounded-xl bg-white p-5 shadow-sm dark:bg-neutral-800">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                <div class="mt-2 inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700 dark:bg-green-900 dark:text-green-300">
                    @if(auth()->user()->posts()->exists())
                        <span class="h-2 w-2 rounded-full bg-green-500 dark:bg-green-300"></span>
                        Active
                    @else
                        <span class="h-2 w-2 rounded-full bg-yellow-400 dark:bg-yellow-300"></span>
                        New Member
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Posts Section -->
        <div class="rounded-xl bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:bg-neutral-800">
            <h2 class="mb-5 text-2xl font-semibold text-gray-900 dark:text-white">
                Recent Posts
            </h2>

            @if(auth()->user()->posts()->exists())
                <div class="space-y-4">
                    @foreach(auth()->user()->posts()->latest()->take(3)->get() as $post)
                        <div class="rounded-lg border border-gray-200 bg-white p-4 transition hover:border-blue-400 dark:border-gray-700 dark:bg-neutral-900">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $post->title }}</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                {{ Str::limit($post->content, 150) }}
                            </p>
                            <div class="mt-3 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>Posted {{ $post->created_at->diffForHumans() }}</span>
                                <a href="{{ route('posts.index') }}"
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    View all →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 text-center dark:border-gray-700 dark:bg-neutral-900">
                    <p class="text-gray-600 dark:text-gray-300">
                        You haven't created any posts yet. 
                        <a href="{{ route('posts.create') }}" class="text-blue-600 hover:underline dark:text-blue-400">
                            Create your first post
                        </a>
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
