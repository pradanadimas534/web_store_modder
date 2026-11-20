<div class="mx-auto max-w-[1280px] px-4 py-8 lg:px-8">
    <div class="mb-4">
        <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Kelola Pengguna & Client Modder</h1>
        <p class="mt-1 text-sm text-[#AAB2C5]">Tambahkan akun client modder baru, promosikan user menjadi modder, atau aktifkan/nonaktifkan akun.</p>
    </div>

    <x-admin-nav />

    <div class="grid gap-6 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <form wire:submit="createSeller" class="rounded-2xl border border-[#30364D] bg-[#121525] p-6 space-y-4">
                <h2 class="text-base font-bold text-[#F8FAFC]">Tambah Client Modder Baru</h2>

                <div>
                    <label for="new-name" class="block text-sm font-semibold text-[#F8FAFC]">Nama Modder</label>
                    <input id="new-name" type="text" wire:model="newName" placeholder="Nama lengkap modder" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('newName') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="new-store" class="block text-sm font-semibold text-[#F8FAFC]">Nama Toko</label>
                    <input id="new-store" type="text" wire:model="newStoreName" placeholder="Contoh: Garasi Simulator ID" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('newStoreName') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="new-email" class="block text-sm font-semibold text-[#F8FAFC]">Email</label>
                    <input id="new-email" type="email" wire:model="newEmail" placeholder="email@example.com" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('newEmail') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="new-password" class="block text-sm font-semibold text-[#F8FAFC]">Kata Sandi Awal</label>
                    <input id="new-password" type="password" wire:model="newPassword" placeholder="Minimal 8 karakter" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('newPassword') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="inline-flex min-h-[44px] w-full items-center justify-center rounded-xl bg-[#7C4DFF] px-4 text-sm font-bold text-white hover:bg-[#6D3DF5]">
                    Buat Akun Client Modder
                </button>
            </form>
        </div>

        <div class="lg:col-span-8">
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 class="text-base font-bold text-[#F8FAFC]">Daftar Pengguna</h2>
                <select wire:model.live="roleFilter" aria-label="Filter peran pengguna" class="min-h-[44px] rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    <option value="">Semua Peran</option>
                    <option value="admin">Admin</option>
                    <option value="seller">Client (Modder)</option>
                    <option value="user">Pembeli (User)</option>
                </select>
            </div>

            <div class="space-y-3">
                @foreach ($users as $user)
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-[#30364D] bg-[#121525] p-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-bold text-[#F8FAFC]">{{ $user->name }}</p>
                                <span class="rounded-md border border-[#30364D] bg-[#1A1E30] px-2 py-0.5 text-xs font-semibold text-[#38BDF8]">
                                    {{ $user->role->label() }}
                                </span>
                                <x-status-badge :status="$user->is_active ? 'active' : 'disabled'" :label="$user->is_active ? 'Aktif' : 'Nonaktif'" />
                            </div>
                            <p class="mt-1 text-xs text-[#AAB2C5]">
                                {{ $user->email }}
                                @if ($user->sellerProfile)
                                    &middot; Toko: {{ $user->sellerProfile->store_name }}
                                @endif
                            </p>
                        </div>

                        @if ($user->id !== auth()->id())
                            <div class="flex flex-wrap gap-2">
                                @if ($user->isBuyer())
                                    <button type="button" wire:click="promoteToSeller({{ $user->id }})" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#7C4DFF]/60 bg-[#1A1E30] px-3.5 text-xs font-semibold text-[#A78BFA]">
                                        Jadikan Modder
                                    </button>
                                @endif
                                <button type="button" wire:click="toggleActive({{ $user->id }})" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-xs font-semibold text-[#F8FAFC]">
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
