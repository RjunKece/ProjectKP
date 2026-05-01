@extends('layouts.karyawan')
@section('title', 'Aktivitas Saya')

@section('content')

<div x-data="{ showAddModal: false, attachType: 'none' }">

{{-- Notifications --}}
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
         class="mb-6 px-5 py-3 rounded-xl bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 text-sm font-medium flex items-center justify-between">
        <span>✅ {{ session('success') }}</span>
        <button @click="show = false" class="text-green-400 hover:text-green-600">✕</button>
    </div>
@endif

{{-- ================= PAGE HEADER ================= --}}
<div class="flex flex-col md:flex-row md:items-end md:justify-between mb-10">
    <div>
        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">Aktivitas Saya</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Catat dan pantau aktivitas kerja harian Anda</p>
    </div>
    <button @click="showAddModal = true; attachType = 'none'"
            class="mt-4 md:mt-0 px-5 py-2.5 rounded-lg text-sm font-semibold
                   bg-gradient-to-r from-[#f5e6b3] to-[#d4af37] text-slate-900
                   hover:opacity-90 shadow-md shadow-[#d4af37]/20 transition flex items-center gap-2">
        ➕ Tambah Aktivitas
    </button>
</div>

{{-- ================= KPI SUMMARY ================= --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
    <div class="erp-card p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-blue-100 to-transparent dark:from-blue-900/20 rounded-bl-full"></div>
        <p class="text-xs uppercase tracking-wide text-slate-400">Total Aktivitas</p>
        <h3 class="text-3xl font-bold mt-2 text-slate-900 dark:text-white">{{ $totalActivities }}</h3>
        <p class="text-xs text-slate-500 mt-1">Seluruh log tercatat</p>
    </div>
    <div class="erp-card p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-amber-100 to-transparent dark:from-amber-900/20 rounded-bl-full"></div>
        <p class="text-xs uppercase tracking-wide text-slate-400">Hari Ini</p>
        <h3 class="text-3xl font-bold mt-2 text-[#d4af37]">{{ $todayActivities }}</h3>
        <p class="text-xs text-slate-500 mt-1">{{ now()->format('d M Y') }}</p>
    </div>
    <div class="erp-card p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-indigo-100 to-transparent dark:from-indigo-900/20 rounded-bl-full"></div>
        <p class="text-xs uppercase tracking-wide text-slate-400">Bulan Ini</p>
        <h3 class="text-3xl font-bold mt-2 text-blue-600">{{ $monthlyActivities }}</h3>
        <p class="text-xs text-slate-500 mt-1">{{ now()->format('F Y') }}</p>
    </div>
    <div class="erp-card p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-green-100 to-transparent dark:from-green-900/20 rounded-bl-full"></div>
        <p class="text-xs uppercase tracking-wide text-slate-400">Minggu Ini</p>
        <h3 class="text-3xl font-bold mt-2 text-green-600">{{ $weeklyActivities }}</h3>
        <p class="text-xs text-slate-500 mt-1">Progres mingguan</p>
    </div>
</div>

{{-- ================= DAILY TARGETS ================= --}}
@if(count($dailyTargets) > 0)
<div class="erp-card p-6 mb-10">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">🎯 Target Harian — {{ $divisionName }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Standar kerja yang harus dicapai hari ini</p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#f5e6b3] text-[#8a6d1d]">
            {{ now()->format('d M Y') }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($dailyTargets as $target)
            @php
                $progressColor = $target->progress >= 100 ? 'bg-green-500' :
                                ($target->progress >= 50 ? 'bg-amber-500' : 'bg-blue-500');
                $isDone = $target->progress >= 100;
            @endphp
            <div class="p-4 rounded-xl border transition-all duration-300 group
                        {{ $isDone
                            ? 'border-green-300 dark:border-green-700 bg-green-50/50 dark:bg-green-900/10'
                            : 'border-slate-200 dark:border-slate-700 hover:border-[#d4af37] hover:shadow-md' }}">

                {{-- Header --}}
                <div class="flex items-start justify-between mb-3">
                    <h4 class="text-sm font-medium text-slate-900 dark:text-white leading-snug flex-1 pr-2">
                        {{ $target->title }}
                    </h4>
                    @if($isDone)
                        <span class="text-green-600 text-lg shrink-0 animate-bounce">✅</span>
                    @endif
                </div>

                {{-- Progress Info --}}
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-bold text-slate-900 dark:text-white">{{ $target->done_today }}</span>
                        / {{ $target->target_count }} {{ $target->unit }}
                    </span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full
                                {{ $isDone ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300'
                                           : ($target->progress >= 50 ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                        {{ $target->progress }}%
                    </span>
                </div>

                {{-- Progress bar --}}
                <div class="w-full h-2.5 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden mb-3">
                    <div class="h-full rounded-full {{ $progressColor }} transition-all duration-700 ease-out"
                         style="width: {{ $target->progress }}%"></div>
                </div>

                {{-- Action Row --}}
                <div class="flex items-center justify-between">
                    @if($target->is_default)
                        <p class="text-[10px] text-slate-400">📌 Standar Perusahaan</p>
                    @else
                        <p class="text-[10px] text-amber-500">⚙️ Tambahan Admin</p>
                    @endif

                    @if(!$isDone)
                        {{-- Quick Complete Button --}}
                        <form method="POST" action="{{ route('karyawan.activities.store') }}">
                            @csrf
                            <input type="hidden" name="target_id" value="{{ $target->id }}">
                            <input type="hidden" name="deskripsi" value="{{ $target->title }}">
                            <input type="hidden" name="tanggal" value="{{ now()->format('Y-m-d\TH:i') }}">
                            <input type="hidden" name="status" value="completed">
                            <button type="submit"
                                    class="px-3 py-1.5 rounded-lg text-[11px] font-semibold
                                           bg-gradient-to-r from-[#f5e6b3] to-[#d4af37] text-slate-900
                                           hover:opacity-90 hover:shadow-md active:scale-95
                                           transition-all duration-200 flex items-center gap-1">
                                ➕ Selesai
                            </button>
                        </form>
                    @else
                        <span class="px-3 py-1.5 rounded-lg text-[11px] font-semibold
                                     bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300
                                     flex items-center gap-1">
                            🎉 Tercapai!
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- ================= FILTER BAR ================= --}}
<div class="erp-card p-5 mb-8">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1 block">Cari</label>
            <input name="search" value="{{ request('search') }}" type="text" placeholder="Cari deskripsi..." class="input w-full">
        </div>
        <div class="w-44">
            <label class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1 block">Status</label>
            <select name="status" class="input w-full">
                <option value="">Semua</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div class="w-44">
            <label class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1 block">Tanggal</label>
            <input name="date" type="date" class="input w-full" value="{{ request('date') }}">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary">🔍 Filter</button>
            @if(request()->hasAny(['search', 'status', 'date']))
                <a href="{{ route('karyawan.activities') }}" class="px-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition">Reset</a>
            @endif
        </div>
    </form>
</div>

{{-- ================= ACTIVITY TABLE ================= --}}
<div class="erp-card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400">
            <tr>
                <th class="px-5 py-4 text-left">No</th>
                <th class="px-5 py-4 text-left">Tanggal</th>
                <th class="px-5 py-4 text-left">Deskripsi</th>
                <th class="px-5 py-4 text-left">Lampiran</th>
                <th class="px-5 py-4 text-left">Status</th>
                <th class="px-5 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($activities as $index => $activity)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                    <td class="px-5 py-4 text-slate-400 text-xs">{{ $activities->firstItem() + $index }}</td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <p class="font-medium text-slate-700 dark:text-slate-300">{{ $activity->tanggal->format('d M Y') }}</p>
                        <p class="text-xs text-slate-400">{{ $activity->tanggal->format('H:i') }} WIB</p>
                    </td>
                    <td class="px-5 py-4 text-slate-700 dark:text-slate-300 max-w-sm">
                        <p class="truncate" title="{{ $activity->deskripsi }}">{{ $activity->deskripsi }}</p>
                        @if($activity->target)
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-medium bg-[#f5e6b3]/50 text-[#8a6d1d]">
                                🎯 {{ $activity->target->title }}
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        @if($activity->file_path)
                            <a href="{{ asset('storage/' . $activity->file_path) }}" target="_blank"
                               class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 hover:bg-blue-100 transition" title="{{ $activity->file_name }}">
                                📎 {{ Str::limit($activity->file_name, 15) }}
                            </a>
                        @endif
                        @if($activity->link)
                            <a href="{{ $activity->link }}" target="_blank"
                               class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 hover:bg-indigo-100 transition">
                                🔗 Link
                            </a>
                        @endif
                        @if(!$activity->file_path && !$activity->link)
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        @php
                            $sc = match($activity->status) {
                                'submitted'   => ['bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300', '📋'],
                                'in_progress' => ['bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300', '⏳'],
                                'completed'   => ['bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300', '✅'],
                                default       => ['bg-slate-100 text-slate-600', '📌'],
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc[0] }}">
                            {{ $sc[1] }} {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                        </span>
                    </td>

                    {{-- AKSI --}}
                    <td class="px-5 py-4 text-center" x-data="{ showEdit: false }">
                        <button @click="showEdit = true"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium
                                       bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400
                                       hover:bg-[#f5e6b3] hover:text-[#8a6d1d]
                                       dark:hover:bg-[#d4af37]/20 dark:hover:text-[#f5e6b3]
                                       border border-slate-200 dark:border-slate-700 hover:border-[#d4af37]
                                       transition-all duration-200 active:scale-95">
                            ✏️ Edit
                        </button>

                        {{-- EDIT MODAL --}}
                        <template x-teleport="body">
                            <div x-show="showEdit" x-transition.opacity x-cloak
                                 class="fixed inset-0 z-[9999] flex items-center justify-center"
                                 @keydown.escape.window="showEdit = false">

                                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showEdit = false"></div>

                                <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] flex flex-col z-[10000]
                                            border border-slate-200 dark:border-slate-700" @click.stop
                                     x-data="{ editAttach: '{{ $activity->file_path ? "file" : ($activity->link ? "link" : "none") }}' }">

                                    {{-- Header --}}
                                    <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between shrink-0">
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">✏️ Edit Aktivitas</h3>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $activity->tanggal->format('d M Y, H:i') }} WIB</p>
                                        </div>
                                        <button @click="showEdit = false" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 transition">✕</button>
                                    </div>

                                    {{-- Body --}}
                                    <div class="flex-1 overflow-y-auto">
                                        <form method="POST" action="{{ route('karyawan.activities.update', $activity->id) }}" enctype="multipart/form-data" class="p-5 space-y-5">
                                            @csrf @method('PUT')

                                            {{-- Status Cards --}}
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">📌 Status</label>
                                                <div class="grid grid-cols-3 gap-2">
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="status" value="submitted" class="peer hidden" {{ $activity->status === 'submitted' ? 'checked' : '' }}>
                                                        <div class="px-3 py-3 rounded-xl border-2 text-center transition-all
                                                                    peer-checked:border-blue-400 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20
                                                                    border-slate-200 dark:border-slate-700 hover:border-slate-300">
                                                            <span class="text-lg block">📋</span>
                                                            <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 block mt-1">Submitted</span>
                                                        </div>
                                                    </label>
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="status" value="in_progress" class="peer hidden" {{ $activity->status === 'in_progress' ? 'checked' : '' }}>
                                                        <div class="px-3 py-3 rounded-xl border-2 text-center transition-all
                                                                    peer-checked:border-amber-400 peer-checked:bg-amber-50 dark:peer-checked:bg-amber-900/20
                                                                    border-slate-200 dark:border-slate-700 hover:border-slate-300">
                                                            <span class="text-lg block">⏳</span>
                                                            <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 block mt-1">In Progress</span>
                                                        </div>
                                                    </label>
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="status" value="completed" class="peer hidden" {{ $activity->status === 'completed' ? 'checked' : '' }}>
                                                        <div class="px-3 py-3 rounded-xl border-2 text-center transition-all
                                                                    peer-checked:border-green-400 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20
                                                                    border-slate-200 dark:border-slate-700 hover:border-slate-300">
                                                            <span class="text-lg block">✅</span>
                                                            <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 block mt-1">Completed</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                            {{-- Deskripsi --}}
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">📝 Deskripsi</label>
                                                <textarea name="deskripsi" rows="3" class="input w-full">{{ $activity->deskripsi }}</textarea>
                                            </div>

                                            {{-- Attachment Picker --}}
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">📎 Lampiran</label>
                                                <div class="flex gap-2">
                                                    <button type="button" @click="editAttach = 'none'"
                                                            :class="editAttach === 'none' ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900' : 'bg-slate-100 dark:bg-slate-800 text-slate-500'"
                                                            class="flex-1 px-3 py-2 rounded-lg text-xs font-medium transition">Tidak Ada</button>
                                                    <button type="button" @click="editAttach = 'file'"
                                                            :class="editAttach === 'file' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-500'"
                                                            class="flex-1 px-3 py-2 rounded-lg text-xs font-medium transition">📄 File</button>
                                                    <button type="button" @click="editAttach = 'link'"
                                                            :class="editAttach === 'link' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-500'"
                                                            class="flex-1 px-3 py-2 rounded-lg text-xs font-medium transition">🔗 Link</button>
                                                </div>
                                            </div>

                                            {{-- File Upload --}}
                                            <div x-show="editAttach === 'file'" x-transition>
                                                @if($activity->file_path)
                                                    <div class="flex items-center gap-2 mb-2 px-3 py-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-xs">
                                                        <span class="text-blue-700 dark:text-blue-300">📎 File saat ini: <strong>{{ $activity->file_name }}</strong></span>
                                                    </div>
                                                @endif
                                                <input type="file" name="file" class="input w-full text-sm">
                                                <p class="text-[10px] text-slate-400 mt-1">Upload baru untuk mengganti (max 10MB)</p>
                                            </div>

                                            {{-- Link --}}
                                            <div x-show="editAttach === 'link'" x-transition>
                                                <input type="url" name="link" class="input w-full" value="{{ $activity->link }}" placeholder="https://...">
                                            </div>

                                            {{-- Submit --}}
                                            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                                                <button type="button" @click="showEdit = false"
                                                        class="px-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-slate-600
                                                               hover:bg-slate-100 dark:hover:bg-slate-800 transition">Batal</button>
                                                <button type="submit"
                                                        class="px-5 py-2.5 rounded-lg text-sm font-semibold
                                                               bg-gradient-to-r from-[#f5e6b3] to-[#d4af37] text-slate-900
                                                               hover:opacity-90 shadow-md shadow-[#d4af37]/20 transition active:scale-95">
                                                    💾 Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl">📭</div>
                            <p class="text-slate-500 dark:text-slate-400 font-medium">Belum ada aktivitas tercatat</p>
                            <p class="text-xs text-slate-400">Klik "Tambah Aktivitas" untuk mulai mencatat</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($activities->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $activities->withQueryString()->links() }}
    </div>
@endif

{{-- ================= ADD ACTIVITY MODAL ================= --}}
<div x-show="showAddModal" x-transition.opacity x-cloak
     class="fixed inset-0 z-[9999] flex items-center justify-center">

    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showAddModal = false"></div>

    <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col z-[10000]
                border border-slate-200 dark:border-slate-700" @click.stop>

        {{-- Header --}}
        <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between shrink-0">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">➕ Tambah Aktivitas</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    @if($divisionName)
                        Divisi <span class="text-[#d4af37] font-medium">{{ $divisionName }}</span> — {{ now()->format('d M Y') }}
                    @else
                        Catat aktivitas kerja harian
                    @endif
                </p>
            </div>
            <button @click="showAddModal = false" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 transition">✕</button>
        </div>

        {{-- Form (scrollable) --}}
        <div class="flex-1 overflow-y-auto">
            <form method="POST" action="{{ route('karyawan.activities.store') }}" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                {{-- Target (optional) --}}
                @if(count($dailyTargets) > 0)
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        🎯 Terkait Target <span class="text-xs text-slate-400">(opsional)</span>
                    </label>
                    <select name="target_id" class="input w-full"
                            onchange="if(this.value){
                                let opt = this.options[this.selectedIndex];
                                document.querySelector('textarea[name=deskripsi]').value = opt.dataset.title;
                            }">
                        <option value="">— Pilih target (atau isi manual) —</option>
                        @foreach($dailyTargets as $target)
                            <option value="{{ $target->id }}"
                                    data-title="{{ $target->title }}"
                                    {{ $target->progress >= 100 ? 'disabled' : '' }}>
                                {{ $target->title }}
                                ({{ $target->done_today }}/{{ $target->target_count }} {{ $target->unit }})
                                {{ $target->progress >= 100 ? ' ✅' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Tanggal --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        📅 Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="tanggal" required value="{{ now()->format('Y-m-d\TH:i') }}" class="input w-full">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        📝 Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi" required rows="3" class="input w-full"
                              placeholder="Jelaskan aktivitas yang dilakukan..."></textarea>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        📌 Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required class="input w-full">
                        <option value="submitted">📋 Submitted</option>
                        <option value="in_progress">⏳ In Progress</option>
                        <option value="completed" selected>✅ Completed</option>
                    </select>
                </div>

                {{-- Attachment Type Picker --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        📎 Lampiran <span class="text-xs text-slate-400">(opsional)</span>
                    </label>
                    <div class="flex gap-2">
                        <button type="button" @click="attachType = 'none'"
                                :class="attachType === 'none' ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'"
                                class="flex-1 px-3 py-2 rounded-lg text-xs font-medium transition">
                            Tanpa Lampiran
                        </button>
                        <button type="button" @click="attachType = 'file'"
                                :class="attachType === 'file' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'"
                                class="flex-1 px-3 py-2 rounded-lg text-xs font-medium transition">
                            📄 Upload File
                        </button>
                        <button type="button" @click="attachType = 'link'"
                                :class="attachType === 'link' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'"
                                class="flex-1 px-3 py-2 rounded-lg text-xs font-medium transition">
                            🔗 Link / URL
                        </button>
                    </div>
                </div>

                {{-- File Upload --}}
                <div x-show="attachType === 'file'" x-transition class="space-y-2">
                    <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-5 text-center
                                hover:border-[#d4af37] transition">
                        <input type="file" name="file" id="fileInput"
                               class="hidden"
                               onchange="document.getElementById('fileName').textContent = this.files[0]?.name || 'Pilih file...'">
                        <label for="fileInput" class="cursor-pointer">
                            <div class="text-3xl mb-2">📁</div>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300" id="fileName">Klik untuk pilih file</p>
                            <p class="text-xs text-slate-400 mt-1">Max 10MB — Gambar, Video, PDF, Excel, dll.</p>
                        </label>
                    </div>

                    {{-- Division-specific file hints --}}
                    @if($divisionName)
                        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg p-2.5">
                            <p class="text-xs text-amber-700 dark:text-amber-300">
                                💡
                                @switch($divisionName)
                                    @case('Konten Kreator')
                                        Upload video konten, foto produk, atau file desain
                                        @break
                                    @case('YouTube')
                                        Upload video, thumbnail, atau script
                                        @break
                                    @case('Sales')
                                        Upload proposal, invoice, atau bukti PO
                                        @break
                                    @case('Marketing')
                                        Upload materi promosi, flyer, atau report campaign
                                        @break
                                    @case('Keuangan')
                                        Upload bukti transaksi, spreadsheet, atau laporan keuangan
                                        @break
                                    @case('Gudang')
                                        Upload foto stok, dokumen pengiriman, atau laporan inventaris
                                        @break
                                    @case('CRM')
                                        Upload data pelanggan, report feedback, atau template email
                                        @break
                                    @case('Admin Marketplace')
                                        Upload screenshot performa toko, foto produk, atau laporan penjualan
                                        @break
                                    @default
                                        Upload file pendukung aktivitas Anda
                                @endswitch
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Link Input --}}
                <div x-show="attachType === 'link'" x-transition>
                    <input type="url" name="link" class="input w-full"
                           placeholder="https://contoh.com/file-atau-resource">
                    @if($divisionName)
                        <p class="text-xs text-slate-400 mt-1.5">
                            💡
                            @switch($divisionName)
                                @case('Konten Kreator')
                                    Link ke TikTok, Instagram, atau platform konten
                                    @break
                                @case('YouTube')
                                    Link ke video YouTube yang diupload
                                    @break
                                @case('Sales')
                                    Link CRM, Google Sheets pipeline, atau dokumen online
                                    @break
                                @case('Marketing')
                                    Link campaign dashboard, Google Ads, atau analytics
                                    @break
                                @case('Admin Marketplace')
                                    Link ke Shopee, Tokopedia, atau marketplace lainnya
                                    @break
                                @default
                                    Link ke resource atau bukti kerja online
                            @endswitch
                        </p>
                    @endif
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showAddModal = false"
                            class="px-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-slate-600
                                   hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-lg text-sm font-semibold
                                   bg-gradient-to-r from-[#f5e6b3] to-[#d4af37] text-slate-900
                                   hover:opacity-90 shadow-md shadow-[#d4af37]/20 transition">
                        💾 Simpan Aktivitas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

@endsection
