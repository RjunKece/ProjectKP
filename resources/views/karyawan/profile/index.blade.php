@extends('layouts.karyawan')
@section('title', 'My Profile')

@section('content')

{{-- Notifications --}}
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
         class="mb-6 px-5 py-3 rounded-xl bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 text-sm font-medium flex items-center justify-between">
        <span>✅ {{ session('success') }}</span>
        <button @click="show = false" class="text-green-400 hover:text-green-600">✕</button>
    </div>
@endif
@if($errors->any())
    <div class="mb-6 px-5 py-3 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 text-sm">
        <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

{{-- ================= PROFILE BANNER ================= --}}
<div class="mb-8 p-8 rounded-2xl bg-gradient-to-r from-slate-900 to-[#1a1c23] border border-slate-800 text-white shadow-xl relative overflow-hidden">
    <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-6">
        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-[#f5e6b3] to-[#d4af37]
                    flex items-center justify-center text-2xl font-bold text-slate-900 shadow-lg shrink-0">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <div class="flex-1">
            <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
            <p class="text-slate-400 text-sm">{{ $user->email }}</p>
            <div class="flex flex-wrap gap-2 mt-3">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-500/30">
                    👤 {{ ucwords(str_replace('_', ' ', optional($user->role)->nama_role ?? 'Karyawan')) }}
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#f5e6b3]/20 text-[#f5e6b3] border border-[#d4af37]/30">
                    {{ optional($user->division)->nama_divisi ?? 'No Division' }}
                </span>
            </div>
        </div>
        <div class="text-right text-sm text-slate-400">
            <p>Member sejak</p>
            <p class="font-semibold text-white">{{ $user->created_at->format('d M Y') }}</p>
        </div>
    </div>
    <div class="absolute -right-10 -bottom-10 opacity-10">
        <svg width="180" height="180" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="0.5">
            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
        </svg>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

    {{-- ================= EDIT PROFILE ================= --}}
    <div class="xl:col-span-2 space-y-8">

        {{-- Edit Info --}}
        <div class="erp-card p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">✏️ Edit Profil</h3>
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="input w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="input w-full">
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg p-3">
                    <p class="text-xs text-amber-700 dark:text-amber-300">
                        ℹ️ Role dan Divisi hanya dapat diubah oleh Admin.
                    </p>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-lg text-sm font-semibold
                                   bg-gradient-to-r from-[#f5e6b3] to-[#d4af37] text-slate-900
                                   hover:opacity-90 shadow-md shadow-[#d4af37]/20 transition">
                        💾 Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="erp-card p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">🔐 Ubah Password</h3>
            <form method="POST" action="{{ route('profile.password') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password Saat Ini</label>
                    <input type="password" name="current_password" required class="input w-full" placeholder="Masukkan password lama">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password Baru</label>
                    <input type="password" name="new_password" required class="input w-full" placeholder="Minimal 6 karakter">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" required class="input w-full" placeholder="Ulangi password baru">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-lg text-sm font-semibold
                                   bg-slate-900 dark:bg-slate-700 text-white
                                   hover:bg-slate-800 dark:hover:bg-slate-600 transition">
                        🔒 Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= SIDEBAR INFO ================= --}}
    <div class="space-y-8">

        {{-- Activity Stats --}}
        <div class="erp-card p-6">
            <h4 class="font-semibold text-slate-900 dark:text-white mb-5">📊 Statistik Aktivitas</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 text-center">
                    <p class="text-xs text-slate-500 dark:text-slate-400">Total</p>
                    <p class="text-2xl font-bold text-[#d4af37] mt-1">{{ $totalActivities }}</p>
                </div>
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 text-center">
                    <p class="text-xs text-slate-500 dark:text-slate-400">Bulan Ini</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $monthlyActivities }}</p>
                </div>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="erp-card p-6">
            <h4 class="font-semibold text-slate-900 dark:text-white mb-5">🏢 Informasi Akun</h4>
            <ul class="space-y-4 text-sm">
                <li class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Role</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ ucwords(str_replace('_', ' ', optional($user->role)->nama_role ?? '-')) }}</span>
                </li>
                <li class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Divisi</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ optional($user->division)->nama_divisi ?? '-' }}</span>
                </li>
                <li class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Terdaftar</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ $user->created_at->format('d M Y') }}</span>
                </li>
            </ul>
        </div>

        {{-- Recent Activities --}}
        <div class="erp-card p-6">
            <h4 class="font-semibold text-slate-900 dark:text-white mb-5">🕐 Aktivitas Terakhir</h4>
            @forelse($recentActivities as $act)
                <div class="flex items-start gap-3 py-2 {{ !$loop->last ? 'border-b border-slate-100 dark:border-slate-800' : '' }}">
                    <div class="w-2 h-2 rounded-full bg-[#d4af37] mt-1.5 shrink-0"></div>
                    <div class="min-w-0">
                        <p class="text-sm text-slate-700 dark:text-slate-300 truncate">{{ $act->deskripsi }}</p>
                        <p class="text-xs text-slate-400">{{ $act->tanggal->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400">Belum ada aktivitas</p>
            @endforelse
        </div>

    </div>
</div>

@endsection
