<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdProofController extends Controller
{
    public function show(Request $request, Ad $ad): StreamedResponse
    {
        $user = $request->user();

        if (! $user || (! $user->isAdmin() && $ad->advertiser_id !== $user->id)) {
            abort(403, 'Anda tidak memiliki akses ke bukti pembayaran iklan ini.');
        }

        if (! $ad->payment_proof_path || ! Storage::disk('local')->exists($ad->payment_proof_path)) {
            abort(404, 'Bukti pembayaran iklan tidak ditemukan.');
        }

        return Storage::disk('local')->response($ad->payment_proof_path);
    }
}
