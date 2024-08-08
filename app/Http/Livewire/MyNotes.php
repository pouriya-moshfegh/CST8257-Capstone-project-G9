<?php

namespace App\Http\Livewire;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MyNotes extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $showEditModal = false;
    public $editNoteId;
    public $editTitle;
    public $editDescription;
    public $editPublishOption;
    public $editFile;


    public function openEditModal($noteId)
    {
        $note = Note::findOrFail($noteId);

        // Verify user ownership
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Set the properties to pre-fill the form
        $this->editNoteId = $noteId;
        $this->editTitle = $note->title;
        $this->editDescription = $note->description;
        $this->editPublishOption = $note->publish_option;

        $this->showEditModal = true;
    }
    public function closeModal()
    {
        $this->editNoteId = null;
        $this->editTitle = null;
        $this->editDescription = null;
        $this->editPublishOption = null;
        $this->showEditModal = false;

    }

    public function updateNote()
    {
        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editDescription' => 'nullable|string',
            'editPublishOption' => 'required|in:public,private',
            'editFile' => 'nullable|file|max:1024', // Validate file
        ]);

        $note = Note::findOrFail($this->editNoteId);

        // Verify user ownership
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Handle file upload
        if ($this->editFile) {
            // Delete old file if exists
            if ($note->file_path) {
                Storage::delete('public/' . $note->file_path);
            }
            $filePath = $this->editFile->store('files', 'public');
        } else {
            $filePath = $note->file_path;
        }

        $note->update([
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'publish_option' => $this->editPublishOption,
            'file_path' => $filePath, // Update file path
        ]);

        $this->showEditModal = false;

        $this->emit('swal:modal', ["title" => "Success", "type" => "success", "text" => "Note updated successfully."]);
    }
    public function download($noteId)
    {
        $note = Note::findOrFail($noteId);

        // Verify user ownership
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return response()->download(storage_path('app/public/' . $note->file_path));
    }


    public function delete($noteId)
    {
        $note = Note::findOrFail($noteId);

        // Verify user ownership
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the file from storage
        if ($note->file_path) {
            Storage::delete('public/' . $note->file_path);
        }

        // Delete the note
        $note->delete();


        $this->emit('swal:modal', ["title" => "Success", "type" => "success", "text" => "Note deleted successfully."]);

    }

    public function render()
    {
        $notes = Note::where('user_id', auth()->id())->paginate(10);

        return view('livewire.my-notes', [
            'notes' => $notes,
        ]);
        }
}
