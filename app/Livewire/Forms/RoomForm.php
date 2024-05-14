<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RoomForm extends Form
{
    use UploadFile;

    #[Validate(rule: 'required|string|between:1,255', onUpdate: true)]
    public string $name;

    #[Validate(rule: 'nullable|image|max:1024', onUpdate: true)]
    public $image;

    #[Validate(rule: 'required|string|min:3', onUpdate: true)]
    public string $description;

    #[Validate(rule: 'required|int|numeric', onUpdate: true)]
    public int $position;

    #[Validate(rule: 'required|int|numeric|exists:constructions,id', onUpdate: true)]
    public int $construction_id;

    public function save(?int $roomId = null): bool
    {
        $validated = $this->validate();

        // Store New Image To Database
        if ($this->image) {
            $filename = Str::of('room-')->append(Str::uuid(), '.', $this->image->extension());
            $path = $this->image->storePubliclyAs(path: 'public/photos', name: $filename);

            // Return false When Store Failed
            if ($path === false) {
                return false;
            }

            $validated['image'] = $path;
        } else {
            unset($validated['image']);
        }

        auth()->user()->rooms()->updateOrCreate(['id' => $roomId], $validated);

        return true;
    }
}
