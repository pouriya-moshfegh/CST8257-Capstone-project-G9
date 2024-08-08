<?php

namespace App\Http\Livewire;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;


class AddNote extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    public $file;
    public $publish_option;

    private $validator;


    public function submit()
    {
        $this->validator = Validator::make([
            'title' => $this->title,
            'description' => $this->description,
            'file' => $this->file,
            'publish_option' => $this->publish_option,
        ], [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'required|file|max:1024', // 1MB Max
            'publish_option' => 'required|in:public,private',
        ]);

        if ($this->validator->fails()) {
            $errorMessages = $this->validator->errors()->all();
            $this->emit('swal:modal', ["title" => "Error", "type" => "error", "text" => $errorMessages]);
            return;
        }
        $filePath = $this->file->store('files', 'public');

        Note::create([
            'title' => $this->title,
            'description' => $this->description,
            'file_path' => $filePath,
            'publish_option' => $this->publish_option,
            'user_id' => Auth::id(), // اضافه کردن user_id به اطلاعات نوت
        ]);
        $this->emit('swal:modal', ["title" => "Success", "type" => "success", "text" => "Note added successfully"]);
        $this->redirect(route("AddNote"));
        return;


    }

    public function render()
    {
        return view('livewire.add-note');
    }
}
