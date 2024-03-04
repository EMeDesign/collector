<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (! function_exists('assetUrl')) {
    /**
     * Generate an asset path Url for the application.
     *
     * @param string $path
     * @param bool|null $secure
     *
     * @return bool|string
     */
    function assetUrl(string $path, ?bool $secure = null): bool|string
    {
        return File::isFile(storage_path(Str::of('app/')->append($path)))
            ? app('url')->asset(Str::of($path)->substrReplace('storage', 0, 6), $secure)
            : false;
    }
}
