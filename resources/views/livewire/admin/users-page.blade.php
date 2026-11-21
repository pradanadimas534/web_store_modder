<div class="mx-auto max-w-[1360px] px-4 py-8 lg:px-8">
    <div class="mb-4">
        <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Kelola Pengguna & Client Modder</h1>
        <p class="mt-1 text-sm text-[#A0AAA2]">Tambahkan akun client modder baru, promosikan user menjadi modder, atau aktifkan/nonaktifkan akun.</p>
    </div>

    <x-admin-nav />

    <div class="grid gap-6 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <form wire:submit="createSeller" class="rounded-2xl border border-[#29342D] bg-[#101614] p-6 space-y-4">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">Tambah Client Modder Baru</h2>

                <div>
                    <label for="new-name" class="block text-xs font-semibold text-[#F2F5F0]">Nama Modder</label>
                    <input id="new-name" type="text" wire:model="newName" placeholder="Nama lengkap modder" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                    @error('newName') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="new-store" class="block text-xs font-semibold text-[#F2F5F0]">Nama Toko</label>
                    <input id="new-store" type="text" wire:model="newStoreName" placeholder="Contoh: Garasi Simulator ID" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                    @error('newStoreName') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="new-email" class="block text-xs font-semibold text-[#F2F5F0]">Email</label>
                    <input id="new-email" type="email" wire:model="newEmail" placeholder="email@example.com" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                    @error('newEmail') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="new-password" class="block text-xs font-semibold text-[#F2F5F0]">Kata Sandi Awal</label>
                    <input id="new-password" type="password" wire:model="newPassword" placeholder="Minimal 8 karakter" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                    @error('newPassword') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="inline-flex min-h-[44px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B]">
                    Buat Akun Client Modder
                </button>
            </form>
        </div>

        <div class="lg:col-span-8">
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">Daftar Pengguna</h2>
                <select wire:model.live="roleFilter" aria-label="Filter peran pengguna" class="min-h-[42px] rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-semibold text-[#F2F5F0]">
                    <option value="">Semua Peran</option>
                    <option value="admin">Admin</option>
                    <option value="seller">Client (Modder)</option>
                    <option value="user">Pembeli (User)</option>
                </select>
            </div>

            <div class="space-y-3">
                @foreach ($users as $user)
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-[#29342D] bg-[#101614] p-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-display text-sm font-bold text-[#F2F5F0]">{{ $user->name }}</p>
                                <span class="rounded-md border border-[#29342D] bg-[#151D19] px-2 py-0.5 text-xs font-bold text-[#7CFF00]">
                                    {{ $user->role->label() }}
                                </span>
                                <x-status-badge :status="$user->is_active ? 'active' : 'disabled'" :label="$user->is_active ? 'Aktif' : 'Nonaktif'" />
                            </div>
                            <p class="mt-1 text-xs text-[#A0AAA2]">
                                {{ $user->email }}
                                @if ($user->sellerProfile)
                                    &middot; Toko: {{ $user->sellerProfile->store_name }}
                                @endif
                            </p>
                        </div>

                        @if ($user->id !== auth()->id())
                            <div class="flex flex-wrap gap-2">
                                @if ($user->isBuyer())
                                    <button type="button" wire:click="promoteToSeller({{ $user->id }})" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#7CFF00]/60 bg-[#151D19] px-3.5 text-xs font-bold text-[#7CFF00]">
                                        Jadikan Modder
                                    </button>
                                @endif
                                <button type="button" wire:click="toggleActive({{ $user->id }})" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-semibold text-[#F2F5F0]">
                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
