<?php

namespace App\Http\Controllers;

use App\Enums\AdStatus;
use App\Models\Ad;
use Illuminate\Http\RedirectResponse;

class AdRedirectController extends Controller
{
    public function __invoke(Ad $ad): RedirectResponse
    {
        if ($ad->status !== AdStatus::Active) {
            abort(404);
        }

        $scheme = parse_url($ad->target_url, PHP_URL_SCHEME);
        if (! in_array($scheme, ['http', 'https'], true)) {
            abort(400, 'URL tujuan iklan tidak valid.');
        }

        $ad->increment('clicks_count');

        return redirect()->away($ad->target_url);
    }
}
