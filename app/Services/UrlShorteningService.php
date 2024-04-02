<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Str;

class UrlShorteningService
{
    /**
     * Generate a unique hash for URL shortening.
     *
     * @return string
     */
    public function generateUniqueHash(): string
    {
        do {
            $hash = Str::random(6);
        } while (Url::where('hash', $hash)->exists());

        return $hash;
    }

    /**
     * Retrieve a URL model by its original URL or its hash.
     *
     * @param string $originalUrl
     * @return Url|null
     */
    public function getUrlByOriginalOrHash(string $originalUrl): ?Url
    {
        return Url::where('original_url', $originalUrl)->first();
    }

    /**
     * Create a new shortened URL entry in the database.
     *
     * @param string $originalUrl
     * @return Url
     */
    public function createShortUrl(string $originalUrl): Url
    {
        return Url::create([
            'original_url' => $originalUrl,
            'hash' => $this->generateUniqueHash(),
        ]);
    }
}
