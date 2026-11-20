<div class="mx-auto max-w-[1280px] px-4 py-8 lg:px-8">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Verifikasi Setoran Biaya Admin Modder</h1>
            <p class="mt-1 text-sm text-[#AAB2C5]">Periksa bukti transfer setoran biaya admin dari modder ke rekening Anda, lalu verifikasi agar tagihan modder lunas.</p>
        </div>

        <select wire:model.live="statusFilter" aria-label="Filter status setoran" class="min-h-[44px] rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
            <option value="">Semua Status</option>
            <option value="pending_verification">Menunggu Verifikasi Admin</option>
            <option value="verified">Terverifikasi Lunas</option>
            <option value="rejected">Ditolak</option>
        </select>
    </div>

    <x-admin-nav />

    @if ($settlements->isEmpty())
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-10 text-center">
            <p class="text-base font-bold text-[#F8FAFC]">Belum ada setoran biaya admin pada filter ini.</p>
        </div>
    @else
        <div class="space-y-5">
            @foreach ($settlements as $settlement)
                <article class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
                    <div class="grid gap-5 lg:grid-cols-12">
                        <div class="lg:col-span-7">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-bold text-[#F8FAFC]">{{ $settlement->settlement_number }}</p>
                                <x-status-badge :status="$settlement->status" />
                            </div>

                            <p class="mt-2 text-sm text-[#F8FAFC]">
                                Modder: <span class="font-bold">{{ $settlement->seller->sellerProfile?->store_name ?? $settlement->seller->name }}</span>
                                ({{ $settlement->seller->email }})
                            </p>
                            <p class="mt-1 text-lg font-extrabold text-[#22C55E]">
                                Rp {{ number_format($settlement->amount, 0, ',', '.') }}
                                <span class="text-xs font-medium text-[#AAB2C5]">({{ $settlement->orders_count }} transaksi mod)</span>
                            </p>
                            <p class="mt-1 text-xs text-[#AAB2C5]">
                                Dikirim pada: {{ $settlement->submitted_at->format('d M Y H:i') }}
                            </p>
                            @if ($settlement->payment_note)
                                <p class="mt-2 text-xs text-[#F8FAFC]">Catatan modder: {{ $settlement->payment_note }}</p>
                            @endif
                            @if ($settlement->admin_note)
                                <p class="mt-2 rounded-lg border border-[#F87171]/40 bg-[#F87171]/15 px-3 py-2 text-xs text-[#F87171]">
                                    Alasan penolakan: {{ $settlement->admin_note }}
                                </p>
                            @endif
                        </div>

                        <div class="flex flex-col justify-between gap-4 rounded-xl border border-[#30364D] bg-[#1A1E30] p-4 lg:col-span-5">
                            <div>
                                <h2 class="text-xs font-bold uppercase tracking-wide text-[#AAB2C5]">Bukti Transfer Setoran</h2>
                                <div class="mt-3 flex items-start gap-3">
                                    <a href="{{ route('settlements.proof', $settlement) }}" target="_blank" rel="noopener noreferrer" class="block h-24 w-24 shrink-0 overflow-hidden rounded-xl border border-[#30364D] bg-[#121525]">
                                        <img
                                            src="{{ route('settlements.proof', $settlement) }}"
                                            alt="Bukti setoran {{ $settlement->settlement_number }}"
                                            class="h-full w-full object-cover"
                                        >
                                    </a>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-[#F8FAFC]">Bukti transfer tersedia</p>
                                        <a href="{{ route('settlements.proof', $settlement) }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex min-h-[38px] items-center rounded-lg border border-[#7C4DFF]/60 bg-[#121525] px-3 text-xs font-semibold text-[#A78BFA] hover:bg-[#242943]">
                                            Buka Bukti Ukuran Penuh
                                        </a>
                                    </div>
                                </div>

                                @if ($settlement->status->value === 'verified')
                                    <p class="mt-3 text-xs font-semibold text-[#22C55E]">
                                        Terverifikasi lunas{{ $settlement->verified_at ? ' pada '.$settlement->verified_at->format('d M Y H:i') : '' }}{{ $settlement->verifier ? ' oleh '.$settlement->verifier->name : '' }}.
                                    </p>
                                @endif
                            </div>

                            @if ($settlement->status->value === 'pending_verification')
                                <div class="space-y-2.5 border-t border-[#30364D] pt-3">
                                    <button
                                        type="button"
                                        wire:click="verifySettlement({{ $settlement->id }})"
                                        class="inline-flex min-h-[44px] w-full items-center justify-center rounded-xl bg-[#22C55E] px-4 text-xs font-bold text-[#0B0D17] hover:bg-[#16A34A]"
                                    >
                                        Verifikasi Setoran Lunas
                                    </button>

                                    <div class="flex flex-col gap-2 sm:flex-row">
                                        <input
                                            type="text"
                                            wire:model="rejectNotes.{{ $settlement->id }}"
                                            placeholder="Alasan penolakan setoran..."
                                            class="min-h-[44px] flex-1 rounded-xl border border-[#30364D] bg-[#121525] px-3 text-xs text-[#F8FAFC]"
                                        >
                                        <button
                                            type="button"
                                            wire:click="rejectSettlement({{ $settlement->id }})"
                                            class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-[#F87171]/50 px-3.5 text-xs font-semibold text-[#F87171] hover:bg-[#F87171]/15"
                                        >
                                            Tolak Setoran
                                        </button>
                                    </div>
                                    @error("rejectNotes.{$settlement->id}")
                                        <p class="text-xs text-[#F87171]">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $settlements->links() }}
        </div>
    @endif
</div>
