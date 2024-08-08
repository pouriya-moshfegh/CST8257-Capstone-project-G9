<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Add Note') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div>
                    @if (session()->has('message'))
                        <div class="">
                            {{ session('message') }}
                        </div>
                    @endif
                    <form wire:submit.prevent="submit" class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" id="title" wire:model="title" class="mt-1 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" wire:model="description" rows="3" class="mt-1 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700">File</label>
                            <input type="file" id="file" wire:model="file" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="publish_option" class="block text-sm font-medium text-gray-700">Publish Option</label>
                            <select id="publish_option" wire:model="publish_option" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option>Select</option>
                                <option value="public">Public</option>
                                <option value="private">Private</option>
                            </select>
                            @error('publish_option') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Add Note
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/livewire/2.5.6/livewire.min.js"></script>
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
