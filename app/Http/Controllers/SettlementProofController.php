<?php

namespace App\Http\Controllers;

use App\Models\SellerFeeSettlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SettlementProofController extends Controller
{
    public function show(Request $request, SellerFeeSettlement $settlement): StreamedResponse
    {
        $user = $request->user();

        if (! $user || (! $user->isAdmin() && $settlement->seller_id !== $user->id)) {
            abort(403, 'Anda tidak memiliki akses ke bukti setoran ini.');
        }

        if (! Storage::disk('local')->exists($settlement->payment_proof_path)) {
            abort(404, 'File bukti setoran tidak ditemukan.');
        }

        return Storage::disk('local')->response($settlement->payment_proof_path);
    }
}
