<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Explore') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($notes as $note)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $note->title }}</h3>
                            <p class="mt-2 text-gray-700">{{ $note->description }}</p>
                            <p class="mt-2 text-sm text-gray-500">
                                Status:
                                <span class="font-medium {{ $note->publish_option === 'public' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ucfirst($note->publish_option) }}
                                </span>
                            </p>
                            <p class="mt-2 text-sm text-gray-500">
                                <span class="font-medium">Author:</span> {{ $note->user->name }}
                            </p>
                            <div class="mt-4 flex items-center space-x-4">
                                <span class="text-sm text-gray-500">Likes: {{ $note->like_count }}</span>
                                <span class="text-sm text-gray-500">Dislikes: {{ $note->dislike_count }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 border-t border-gray-200">
                            <button wire:click="like('{{ $note->id }}')" class="text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7M12 9v12" />
                                </svg>
                            </button>

                            <button wire:click="dislike('{{ $note->id }}')" class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9l7 7 7-7M12 15V3" />
                                </svg>
                            </button>
                            <button wire:click="loadComments({{ $note->id }})" class="text-gray-600 hover:text-gray-800 relative">
                                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 11a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="absolute top-0 right-0 inline-block w-4 h-4 bg-red-600 text-white text-xs font-semibold text-center rounded-full">{{ $note->comments_count }}</span>
                            </button>
                            <button wire:click="download('{{ $note->id }}')" class="text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4l4 4m0 0l4-4h4M12 3v12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $notes->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    @if ($showComments)
        <div class="fixed inset-0 z-50 overflow-hidden flex items-end justify-end p-4 bg-gray-800 bg-opacity-75">
            <div class="bg-white rounded-lg w-full max-w-md overflow-auto">
                <div class="flex justify-between items-center px-4 py-2 border-b">
                    <h2 class="text-lg font-semibold">Comments</h2>
                    <button wire:click="closeComments" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="px-4 py-2">
                    <ul class="space-y-2">
                        @foreach ($comments as $comment)
                            <li class="border-b pb-2">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold">{{ $comment->user->name }}</span>
                                    <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mt-1 text-gray-700">{{ $comment->content }}</p>
                            </li>
                        @endforeach
                    </ul>
                    <form wire:submit.prevent="addComment" class="mt-4">
                        <textarea wire:model="newComment" class="w-full border rounded-md p-2" rows="3" placeholder="Add a comment..."></textarea>
                        @error('newComment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <div class="mt-2 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Post Comment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
