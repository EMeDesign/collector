<?php

if (! function_exists('assetUrl')) {
    /**
     * Generate an asset path Url for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function assetUrl(string $path, ?bool $secure = null): string
    {
        return app('url')->asset(str($path)->substrReplace('storage', 0, 6), $secure);
    }
}
