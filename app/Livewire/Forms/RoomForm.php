<?php

namespace App\Livewire\Forms;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RoomForm extends Form
{
    #[Validate(rule: 'required|string|between:1,255', onUpdate: true)]
    public string $name;

    #[Validate(rule: 'nullable|image|max:1024', onUpdate: true)]
    public $image;

    #[Validate(rule: 'required|string|min:3', onUpdate: true)]
    public string $description;

    #[Validate(rule: 'required|int|numeric', onUpdate: true)]
    public int $position;

    /**
     * Delete upload file.
     *
     * @param array $content
     *
     * @return void
     */
    public function deleteUpload(array $content): void
    {
        /*
         the $content contains:
         [
             'temporary_name',
             'real_name',
             'extension',
             'size',
             'path',
             'url',
         ]
         */

        if (!$this->image) {
            return;
        }

        $files = Arr::wrap($this->image);

        /** @var UploadedFile $file */
        $file = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() === $content['temporary_name'])->first();

        // 1. Here we delete the file. Even if we have a error here, we simply
        // ignore it because as long as the file is not persisted, it is
        // temporary and will be deleted at some point if there is a failure here.
        rescue(fn () => $file->delete(), report: false);

        $collect = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() !== $content['temporary_name']);

        // 2. We guarantee restore of remaining files regardless of upload
        // type, whether you are dealing with multiple or single uploads
        $this->image = is_array($this->image) ? $collect->toArray() : $collect->first();
    }

    public function save(?int $roomId = null): bool
    {
        $validated = $this->validate();

        // Store New Image To Database
        if ($this->image) {
            $filename = 'room'.Str::uuid().$this->image->extension();
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
