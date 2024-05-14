<?php

namespace App\Livewire\Forms;

use App\Models\Item;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ItemForm extends Form
{
    use UploadFile;

    #[Validate(rule: 'required|string|between:1,255', onUpdate: false)]
    public string $name;

    #[Validate(rule: 'nullable|image|max:1024', onUpdate: true)]
    public $image;

    #[Validate(rule: 'required|string|min:3', onUpdate: false)]
    public string $description;

    #[Validate(rule: 'required|int|numeric', onUpdate: false)]
    public int $quantity;

    #[Validate(rule: 'required|int|numeric|exists:units,id', onUpdate: false)]
    public int $unit_id;

    #[Validate(rule: 'required|int|numeric|exists:furniture,id', onUpdate: false)]
    public int $furniture_id;

    #[Validate(rule: 'required|int|numeric|exists:categories,id', onUpdate: false)]
    public int $category_id;

    #[Validate(rule: ['keywords' => 'sometimes|array', 'keywords.*' => 'int|numeric|exists:keywords,id'], onUpdate: false)]
    public array $keywords;

    #[Validate(rule: 'nullable|date', onUpdate: false)]
    public $obtained_at;

    #[Validate(rule: 'nullable|date', onUpdate: false)]
    public $expired_at;

    public function setForm(Item $item)
    {
        $item->image = null;
        $this->fill($item);
        $this->keywords = $item->keywords->pluck('id')->toArray();
    }

    public function save(?int $itemId = null): bool
    {
        $validated = $this->validate();

        // Store New Image To Database
        if ($this->image) {
            $filename = Str::of('item-')->append(Str::uuid(), '.', $this->image->extension());
            $path = $this->image->storePubliclyAs(path: 'public/photos', name: $filename);

            // Return false When Store Failed
            if ($path === false) {
                return false;
            }

            $validated['image'] = $path;
        } else {
            unset($validated['image']);
        }

        auth()->user()->items()->updateOrCreate(['id' => $itemId], $validated);

        auth()->user()->keywords()->wherePivot('item_id', '=', $itemId)->detach();

        auth()->user()->keywords()->attach($this->keywords, ['item_id' => $itemId]);

        return true;
    }
}
