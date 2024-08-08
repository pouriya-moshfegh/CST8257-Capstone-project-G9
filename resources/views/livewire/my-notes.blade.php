<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Notes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <!-- Container with padding -->
        <div class="container mx-auto px-4">
            <!-- Grid layout for cards -->
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
                        </div>
                        <div class="flex items-center justify-between p-4 border-t border-gray-200">
                            <!-- Download Icon -->
                            <button wire:click="download('{{ $note->id }}')" class="text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4l4 4m0 0l4-4h4M12 3v12" />
                                </svg>
                            </button>

                            <!-- Edit Icon -->
                            <button wire:click="openEditModal('{{ $note->id }}')" class="text-yellow-600 hover:text-yellow-800">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 5l3 3m-3 0l3 3M12 7h.01M12 12h.01M12 17h.01M9 21h6a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2z" />
                                </svg>
                            </button>

                            <!-- Delete Icon -->
                            <button wire:click="delete('{{ $note->id }}')" class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 2L6 4H4v2h16V4h-2V2H6zM4 6v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6H4z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $notes->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <!-- Edit Note Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 z-50 overflow-auto bg-gray-800 bg-opacity-50">
        <div class="relative top-1/2 mx-auto w-full max-w-lg transform -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg">
            <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Note</h3>

            <form wire:submit.prevent="updateNote">
                <input type="hidden" wire:model="editNoteId">

                <div class="mb-4">
                    <label for="edit_title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="edit_title" wire:model="editTitle" class="mt-1 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('editTitle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="edit_description" wire:model="editDescription" rows="3" class="mt-1 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    @error('editDescription') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="edit_publish_option" class="block text-sm font-medium text-gray-700">Publish Option</label>
                    <select id="edit_publish_option" wire:model="editPublishOption" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                    </select>
                    @error('editPublishOption') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="edit_file" class="block text-sm font-medium text-gray-700">File</label>
                    <input type="file" id="edit_file" wire:model="editFile" class="mt-1 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('editFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
    <script>

        document.addEventListener('livewire:load', function () {
            Livewire.on('swal:modal', data => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-right",
                    showConfirmButton: false,
                    timer: 3000,

                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    title: data.title,
                    icon: data.type,
                    text: data.text,

                    animation: true
                });
            });

        });
    </script>
</div>
