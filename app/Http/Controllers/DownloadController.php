<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Download;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function free(Request $request, Product $product): StreamedResponse
    {
        if (! $product->isActive() || ! $product->isFree()) {
            abort(403, 'Mod ini tidak tersedia untuk unduhan gratis langsung.');
        }

        if (! Storage::disk('local')->exists($product->file_path)) {
            abort(404, 'File mod tidak ditemukan di server.');
        }

        Download::create([
            'user_id' => $request->user()?->id,
            'product_id' => $product->id,
            'ip_address' => $request->ip(),
            'downloaded_at' => now(),
        ]);

        $product->increment('downloads_count');

        return Storage::disk('local')->download(
            $product->file_path,
            $product->file_original_name
        );
    }

    public function paid(Request $request, OrderItem $orderItem): StreamedResponse
    {
        $user = $request->user();
        $orderItem->loadMissing(['order', 'product']);

        if (! $user || ! $orderItem->order) {
            abort(403);
        }

        $order = $orderItem->order;

        if ($order->buyer_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh file ini.');
        }

        if ($order->status !== OrderStatus::Completed) {
            abort(403, 'Pembayaran belum terverifikasi.');
        }

        $product = $orderItem->product;

        if (! $product || ! Storage::disk('local')->exists($product->file_path)) {
            abort(404, 'File mod tidak ditemukan di server.');
        }

        Download::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_item_id' => $orderItem->id,
            'ip_address' => $request->ip(),
            'downloaded_at' => now(),
        ]);

        $product->increment('downloads_count');

        return Storage::disk('local')->download(
            $product->file_path,
            $product->file_original_name
        );
    }
}
