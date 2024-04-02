<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Url;
use App\Services\UrlShorteningService;
use App\Services\GoogleSafeBrowsingService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    protected $urlShorteningService;
    protected $googleSafeBrowsingService;

    public function __construct(UrlShorteningService $urlShorteningService, GoogleSafeBrowsingService $googleSafeBrowsingService)
    {
        $this->urlShorteningService = $urlShorteningService;
        $this->googleSafeBrowsingService = $googleSafeBrowsingService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'original_url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $originalUrl = $request->input('original_url');

        try {
            $safetyMessage = $this->googleSafeBrowsingService->checkUrl($originalUrl);

            $url = $this->urlShorteningService->getUrlByOriginalOrHash($originalUrl) ?? $this->urlShorteningService->createShortUrl($originalUrl);
            return response()->json([
                'shortUrl' => url('/' . $url->hash),
                'safetyMessage' => $safetyMessage
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function redirect($hash)
    {
        try {
            $url = Url::where('hash', $hash)->firstOrFail();
            return Redirect::to($url->original_url);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'URL not found.'], 404);
        }
    }
}
