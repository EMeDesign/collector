<?php

namespace App\Livewire\Forms;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

/**
 * @property $image
 */
trait UploadFile
{
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
}
