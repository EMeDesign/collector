<?php

namespace App\Livewire\Forms;

use App\Models\Furniture;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FurnitureForm extends Form
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

    #[Validate(rule: 'required|bool', onUpdate: true)]
    public bool $is_private;
    #[Validate(rule: 'required|int|numeric|exists:rooms,id', onUpdate: true)]
    public int $room_id;

    public function setFurniture(Furniture $furniture): void
    {
        $this->name = $furniture->name;

        $this->description = $furniture->description;

        $this->position = $furniture->position;

        $this->is_private = $furniture->is_private;

        $this->room_id = $furniture->room_id;
    }

    public function save(?int $furnitureId = null): bool
    {
        $validated = $this->validate();

        // Store New Image To Database
        if ($this->image) {
            $filename = Str::of('furniture-')->append(Str::uuid(), '.', $this->image->extension());
            $path = $this->image->storePubliclyAs(path: 'public/photos', name: $filename);

            // Return false When Store Failed
            if ($path === false) {
                return false;
            }

            $validated['image'] = $path;
        } else {
            unset($validated['image']);
        }

        auth()->user()->furniture()->updateOrCreate(['id' => $furnitureId], $validated);

        return true;
    }
}
