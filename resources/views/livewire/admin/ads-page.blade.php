<div class="mx-auto max-w-[1280px] px-4 py-8 lg:px-8">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Kelola & Verifikasi Iklan (AdSense)</h1>
            <p class="mt-1 text-sm text-[#AAB2C5]">Periksa bukti pembayaran jasa iklan dari pengiklan, verifikasi pembayaran, lalu kelola penayangan banner.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <select wire:model.live="paymentStatusFilter" aria-label="Filter status pembayaran iklan" class="min-h-[44px] rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                <option value="">Semua Status Bayar</option>
                <option value="pending_verification">Menunggu Verifikasi Bayar</option>
                <option value="unpaid">Belum Dibayar</option>
                <option value="paid">Lunas</option>
                <option value="rejected">Pembayaran Ditolak</option>
            </select>

            <select wire:model.live="statusFilter" aria-label="Filter status tayang iklan" class="min-h-[44px] rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                <option value="">Semua Status Tayang</option>
                <option value="pending">Menunggu Review</option>
                <option value="active">Aktif</option>
                <option value="paused">Dijeda</option>
                <option value="rejected">Ditolak</option>
            </select>
        </div>
    </div>

    <x-admin-nav />

    @if ($ads->isEmpty())
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-10 text-center">
            <p class="text-base font-bold text-[#F8FAFC]">Belum ada pengajuan iklan pada filter ini.</p>
        </div>
    @else
        <div class="space-y-5">
            @foreach ($ads as $ad)
                <article class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
                    <div class="grid gap-5 lg:grid-cols-12">
                        <div class="lg:col-span-7">
                            <div class="flex flex-wrap items-center gap-2">
                                <x-status-badge :status="$ad->status" />
                                @if ($ad->payment_status)
                                    <x-status-badge :status="$ad->payment_status" />
                                @endif
                                <span class="rounded-md border border-[#30364D] bg-[#1A1E30] px-2 py-0.5 text-xs font-semibold text-[#38BDF8]">
                                    {{ $ad->placement->label() }}
                                </span>
                            </div>

                            <h2 class="mt-3 text-base font-bold text-[#F8FAFC]">{{ $ad->title }}</h2>
                            <p class="mt-1 text-xs leading-relaxed text-[#AAB2C5]">{{ $ad->description }}</p>

                            <dl class="mt-4 grid gap-2 rounded-xl border border-[#30364D] bg-[#1A1E30] p-3.5 text-xs sm:grid-cols-3">
                                <div>
                                    <dt class="text-[#AAB2C5]">Tarif Slot</dt>
                                    <dd class="mt-0.5 font-semibold text-[#F8FAFC]">Rp {{ number_format($ad->slot_price, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[#AAB2C5]">Biaya Admin</dt>
                                    <dd class="mt-0.5 font-semibold text-[#F8FAFC]">Rp {{ number_format($ad->admin_fee, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[#AAB2C5]">Total Tagihan</dt>
                                    <dd class="mt-0.5 font-bold text-[#22C55E]">Rp {{ number_format($ad->total_price, 0, ',', '.') }}</dd>
                                </div>
                            </dl>

                            <p class="mt-3 text-xs text-[#AAB2C5]">
                                Pengiklan: <span class="font-semibold text-[#F8FAFC]">{{ $ad->advertiser_name }}</span> ({{ $ad->advertiser_email }})
                                &middot; Impresi: {{ number_format($ad->impressions_count) }}
                                &middot; Klik: {{ number_format($ad->clicks_count) }}
                            </p>
                            <p class="mt-1 truncate text-xs text-[#A78BFA]">URL Tujuan: {{ $ad->target_url }}</p>

                            @if ($ad->admin_note)
                                <p class="mt-2 rounded-lg border border-[#F87171]/40 bg-[#F87171]/15 px-3 py-2 text-xs text-[#F87171]">
                                    Catatan penolakan: {{ $ad->admin_note }}
                                </p>
                            @endif
                        </div>

                        <div class="flex flex-col justify-between gap-4 rounded-xl border border-[#30364D] bg-[#1A1E30] p-4 lg:col-span-5">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wide text-[#AAB2C5]">Bukti Pembayaran Jasa Iklan</h3>

                                @if ($ad->payment_proof_path)
                                    <div class="mt-3 flex items-start gap-3">
                                        <a href="{{ route('ads.proof', $ad) }}" target="_blank" rel="noopener noreferrer" class="block h-24 w-24 shrink-0 overflow-hidden rounded-xl border border-[#30364D] bg-[#121525]">
                                            <img
                                                src="{{ route('ads.proof', $ad) }}"
                                                alt="Bukti pembayaran iklan {{ $ad->title }}"
                                                class="h-full w-full object-cover"
                                            >
                                        </a>
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-[#F8FAFC]">Bukti transfer tersedia</p>
                                            @if ($ad->paid_at)
                                                <p class="mt-0.5 text-xs text-[#AAB2C5]">Diunggah: {{ $ad->paid_at->format('d M Y H:i') }}</p>
                                            @endif
                                            <a href="{{ route('ads.proof', $ad) }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex min-h-[38px] items-center rounded-lg border border-[#7C4DFF]/60 bg-[#121525] px-3 text-xs font-semibold text-[#A78BFA] hover:bg-[#242943]">
                                                Buka Bukti Ukuran Penuh
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <p class="mt-2 rounded-lg border border-[#FBBF24]/40 bg-[#FBBF24]/15 px-3 py-2 text-xs text-[#FBBF24]">
                                        Belum ada bukti pembayaran yang diunggah oleh pengiklan.
                                    </p>
                                @endif

                                @if ($ad->payment_status?->value === 'paid')
                                    <p class="mt-3 text-xs font-semibold text-[#22C55E]">
                                        Pembayaran lunas{{ $ad->verified_at ? ' pada '.$ad->verified_at->format('d M Y H:i') : '' }}{{ $ad->verifier ? ' oleh '.$ad->verifier->name : '' }}.
                                    </p>
                                @endif
                            </div>

                            <div class="space-y-2.5 border-t border-[#30364D] pt-3">
                                @if ($ad->payment_status?->value !== 'paid')
                                    <button
                                        type="button"
                                        wire:click="verifyPaymentAndActivate({{ $ad->id }})"
                                        class="inline-flex min-h-[44px] w-full items-center justify-center rounded-xl bg-[#22C55E] px-4 text-xs font-bold text-[#0B0D17] hover:bg-[#16A34A]"
                                    >
                                        Verifikasi Pembayaran & Aktifkan Iklan
                                    </button>

                                    <div class="flex flex-col gap-2 sm:flex-row">
                                        <input
                                            type="text"
                                            wire:model="rejectNotes.{{ $ad->id }}"
                                            placeholder="Alasan penolakan pembayaran..."
                                            class="min-h-[44px] flex-1 rounded-xl border border-[#30364D] bg-[#121525] px-3 text-xs text-[#F8FAFC]"
                                        >
                                        <button
                                            type="button"
                                            wire:click="rejectPayment({{ $ad->id }})"
                                            class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-[#F87171]/50 px-3.5 text-xs font-semibold text-[#F87171] hover:bg-[#F87171]/15"
                                        >
                                            Tolak Bayar
                                        </button>
                                    </div>
                                    @error("rejectNotes.{$ad->id}")
                                        <p class="text-xs text-[#F87171]">{{ $message }}</p>
                                    @enderror
                                @else
                                    <div class="flex flex-wrap gap-2">
                                        @if ($ad->status->value !== 'active')
                                            <button type="button" wire:click="setStatus({{ $ad->id }}, 'active')" class="inline-flex min-h-[44px] flex-1 items-center justify-center rounded-xl bg-[#22C55E] px-3.5 text-xs font-bold text-[#0B0D17]">
                                                Aktifkan Lagi
                                            </button>
                                        @else
                                            <button type="button" wire:click="setStatus({{ $ad->id }}, 'paused')" class="inline-flex min-h-[44px] flex-1 items-center justify-center rounded-xl border border-[#FBBF24]/50 px-3.5 text-xs font-semibold text-[#FBBF24]">
                                                Jeda Tayang
                                            </button>
                                        @endif
                                    </div>
                                @endif

                                <button
                                    type="button"
                                    wire:click="deleteAd({{ $ad->id }})"
                                    wire:confirm="Hapus iklan ini secara permanen?"
                                    class="inline-flex min-h-[40px] w-full items-center justify-center rounded-xl border border-[#30364D] px-3.5 text-xs font-semibold text-[#AAB2C5] hover:border-[#F87171]/50 hover:text-[#F87171]"
                                >
                                    Hapus Iklan
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $ads->links() }}
        </div>
    @endif
</div>
