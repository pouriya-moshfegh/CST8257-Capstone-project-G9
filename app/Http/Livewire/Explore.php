<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Explore extends Component
{
    use WithPagination;

    public $noteId;
    public $newComment;
    public $showComments = false;
    public $comments = [];

    public function like($noteId)
    {
        $note = Note::findOrFail($noteId);
        $user = auth()->user();

        // Toggle like
        $like = Like::updateOrCreate(
            ['user_id' => $user->id, 'note_id' => $noteId],
            ['type' => 'like']
        );

        // Remove dislike if it exists
        Like::where('user_id', $user->id)
            ->where('note_id', $noteId)
            ->where('type', 'dislike')
            ->delete();

        $this->emit('swal:modal', ["title" => "Success", "type" => "success", "text" => "Liked successfully."]);
    }

    public function dislike($noteId)
    {
        $note = Note::findOrFail($noteId);
        $user = auth()->user();

        // Toggle dislike
        $dislike = Like::updateOrCreate(
            ['user_id' => $user->id, 'note_id' => $noteId],
            ['type' => 'dislike']
        );

        // Remove like if it exists
        Like::where('user_id', $user->id)
            ->where('note_id', $noteId)
            ->where('type', 'like')
            ->delete();

        $this->emit('swal:modal', ["title" => "Success", "type" => "success", "text" => "Disliked successfully."]);
    }

    public function loadComments($noteId)
    {
        $this->noteId = $noteId;
        $this->newComment = '';
        $this->comments = Comment::where('note_id', $noteId)->with('user')->latest()->get();
        $this->showComments = true;
    }

    public function closeComments()
    {
        $this->showComments = false;
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|max:500',
        ]);

        Comment::create([
            'note_id' => $this->noteId,
            'user_id' => auth()->id(),
            'content' => $this->newComment,
        ]);

        $this->newComment = '';
        $this->comments = Comment::where('note_id', $this->noteId)->with('user')->latest()->get();
    }
    public function download($noteId)
    {
        $note = Note::findOrFail($noteId);

        // Verify user ownership


        return response()->download(storage_path('app/public/' . $note->file_path));
    }
    public function render()
    {
        $notes = Note::withCount('comments') // Ensure comments count is available
        ->where('publish_option', 'public')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $comments = $this->noteId ? Comment::where('note_id', $this->noteId)->with('user')->latest()->get() : [];

        return view('livewire.explore', [
            'notes' => $notes,
            'comments' => $comments,
        ]);
    }
}
