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
use Livewire\Livewire;
use Tests\TestCase;

class AdSenseWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_ad_direct_payment_to_admin_account_activation_and_click_tracking(): void
    {
        // Submitting with wrong nominal fails validation; exact nominal (50000 slot + 5000 admin = 55000) succeeds
        Livewire::test(SubmitAdPage::class)
            ->set('advertiser_name', 'Komunitas Konvoi ID')
            ->set('advertiser_email', 'promo@konvoi.id')
            ->set('title', 'Server Konvoi ETS2 Malam Minggu')
            ->set('description', 'Gabung server konvoi komunitas simulator Indonesia setiap akhir pekan.')
            ->set('cta_text', 'Gabung Server')
            ->set('target_url', 'https://example.com/konvoi')
            ->set('placement', AdPlacement::CatalogBanner->value)
            ->call('openPaymentGateway')
            ->assertSet('showPaymentGateway', true)
            ->set('paymentAmount', 10000)
            ->call('submitAd')
            ->assertHasErrors('paymentAmount')
            ->set('paymentAmount', 55000)
            ->call('submitAd')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $ad = Ad::where('title', 'Server Konvoi ETS2 Malam Minggu')->firstOrFail();
        $this->assertSame(AdStatus::Active, $ad->status);
        $this->assertSame(AdPaymentStatus::Paid, $ad->payment_status);
        $this->assertSame(50000, $ad->slot_price);
        $this->assertSame(5000, $ad->admin_fee);
        $this->assertSame(55000, $ad->total_price);

        // Admin dashboard immediately reflects paid ad operational income
        $admin = User::factory()->create(['role' => UserRole::Admin]);
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

        // Admin can still pause or manage the ad from Admin AdsPage
        Livewire::actingAs($admin)
            ->test(AdminAdsPage::class)
            ->call('setStatus', $ad->id, 'paused')
            ->assertHasNoErrors();

        $this->assertSame(AdStatus::Paused, $ad->fresh()->status);
    }
}
