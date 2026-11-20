<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderProofController extends Controller
{
    public function show(Request $request, Order $order): StreamedResponse
    {
        Gate::authorize('view', $order);

        if (! $order->payment_proof_path || ! Storage::disk('local')->exists($order->payment_proof_path)) {
            abort(404, 'Bukti pembayaran tidak ditemukan.');
        }

        return Storage::disk('local')->response($order->payment_proof_path);
    }
}
