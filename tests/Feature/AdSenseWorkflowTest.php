<?php

namespace Tests\Feature;

use App\Enums\AdPaymentStatus;
use App\Enums\AdPlacement;
use App\Enums\AdStatus;
use App\Enums\UserRole;
use App\Livewire\Admin\AdsPage as AdminAdsPage;
use App\Livewire\Store\SubmitAdPage;
use App\Models\Ad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdSenseWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_ad_submission_with_fee_payment_proof_moderation_and_click_tracking(): void
    {
        Storage::fake('local');
        $proofFile = UploadedFile::fake()->image('bukti-iklan.jpg', 600, 600);

        // Submitting ad without payment proof must fail validation
        Livewire::test(SubmitAdPage::class)
            ->set('advertiser_name', 'Komunitas Konvoi ID')
            ->set('advertiser_email', 'promo@konvoi.id')
            ->set('title', 'Server Konvoi ETS2 Malam Minggu')
            ->set('description', 'Gabung server konvoi komunitas simulator Indonesia setiap akhir pekan.')
            ->set('cta_text', 'Gabung Server')
            ->set('target_url', 'https://example.com/konvoi')
            ->set('placement', AdPlacement::CatalogBanner->value)
            ->call('submitAd')
            ->assertHasErrors('paymentProof');

        Livewire::test(SubmitAdPage::class)
            ->set('advertiser_name', 'Komunitas Konvoi ID')
            ->set('advertiser_email', 'promo@konvoi.id')
            ->set('title', 'Server Konvoi ETS2 Malam Minggu')
            ->set('description', 'Gabung server konvoi komunitas simulator Indonesia setiap akhir pekan.')
            ->set('cta_text', 'Gabung Server')
            ->set('target_url', 'https://example.com/konvoi')
            ->set('placement', AdPlacement::CatalogBanner->value)
            ->set('paymentProof', $proofFile)
            ->call('submitAd')
            ->assertHasNoErrors();

        $ad = Ad::where('title', 'Server Konvoi ETS2 Malam Minggu')->firstOrFail();
        $this->assertSame(AdStatus::Pending, $ad->status);
        $this->assertSame(AdPaymentStatus::PendingVerification, $ad->payment_status);
        $this->assertSame(50000, $ad->slot_price);
        $this->assertSame(5000, $ad->admin_fee);
        $this->assertSame(55000, $ad->total_price);
        Storage::disk('local')->assertExists($ad->payment_proof_path);

        // Pending ad should not display or redirect
        $this->get(route('home'))->assertDontSee('Server Konvoi ETS2 Malam Minggu');
        $this->get(route('ads.redirect', $ad))->assertNotFound();

        // Non-admin user cannot access private ad payment proof
        $otherUser = User::factory()->create(['role' => UserRole::User]);
        $this->actingAs($otherUser)->get(route('ads.proof', $ad))->assertForbidden();

        // Admin views proof, sees preview page, cannot activate without verifying payment, can reject with reason, then verifies & activates
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->actingAs($admin)->get(route('ads.proof', $ad))->assertOk();
        $this->actingAs($admin)->get(route('admin.ads'))
            ->assertOk()
            ->assertSee('Bukti Pembayaran Jasa Iklan')
            ->assertSee('Verifikasi Pembayaran & Aktifkan Iklan', false);

        Livewire::actingAs($admin)
            ->test(AdminAdsPage::class)
            ->call('setStatus', $ad->id, 'active');
        $this->assertSame(AdStatus::Pending, $ad->fresh()->status);

        Livewire::actingAs($admin)
            ->test(AdminAdsPage::class)
            ->call('rejectPayment', $ad->id)
            ->assertHasErrors("rejectNotes.{$ad->id}");

        Livewire::actingAs($admin)
            ->test(AdminAdsPage::class)
            ->call('verifyPaymentAndActivate', $ad->id)
            ->assertHasNoErrors();

        $ad->refresh();
        $this->assertSame(AdStatus::Active, $ad->status);
        $this->assertSame(AdPaymentStatus::Paid, $ad->payment_status);

        // Admin dashboard reflects paid ad operational income
        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('55.000');

        // Active ad renders on homepage and counts impression
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Server Konvoi ETS2 Malam Minggu');

        $this->assertGreaterThanOrEqual(1, $ad->fresh()->impressions_count);

        // Clicking ad redirects safely and counts click
        $this->get(route('ads.redirect', $ad))
            ->assertRedirect('https://example.com/konvoi');

        $this->assertSame(1, $ad->fresh()->clicks_count);
    }
}
