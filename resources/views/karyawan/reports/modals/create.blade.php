<div x-show="showCreateModal" x-transition.opacity x-cloak
     class="fixed inset-0 z-[9999] flex items-center justify-center">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showCreateModal = false"></div>

    <div x-data="{ selScope: 'division' }"
         class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col z-[10000] border border-slate-200 dark:border-slate-700"
         @click.stop>

        {{-- Header --}}
        <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between shrink-0">
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">✍️ Buat Laporan Baru</h2>
                <p class="text-sm text-slate-500 mt-0.5">Laporkan kebutuhan, kendala, saran, atau progress kerja</p>
            </div>
            <button @click="showCreateModal = false"
                    class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:bg-slate-200 transition">✕</button>
        </div>

        {{-- Body --}}
        <div class="flex-1 overflow-y-auto px-6 py-6">
            <form method="POST" action="{{ route('karyawan.reports.store') }}" class="space-y-5" id="karyawanReportForm">
                @csrf

                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        Judul Laporan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required class="input w-full" placeholder="Contoh: Kebutuhan peralatan divisi IT">
                </div>

                {{-- Tipe Laporan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        📌 Jenis Laporan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @php
                            $types = [
                                ['val' => 'kebutuhan', 'icon' => '📋', 'label' => 'Kebutuhan'],
                                ['val' => 'kendala', 'icon' => '⚠️', 'label' => 'Kendala'],
                                ['val' => 'saran', 'icon' => '💡', 'label' => 'Saran'],
                                ['val' => 'progress', 'icon' => '📈', 'label' => 'Progress'],
                                ['val' => 'lainnya', 'icon' => '📄', 'label' => 'Lainnya'],
                            ];
                        @endphp
                        @foreach($types as $i => $t)
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="{{ $t['val'] }}" class="peer sr-only" {{ $i === 0 ? 'checked' : '' }}>
                            <div class="px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700
                                        peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-slate-800
                                        text-center transition-all text-sm">
                                <span class="text-lg">{{ $t['icon'] }}</span>
                                <p class="text-xs font-medium mt-0.5">{{ $t['label'] }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Scope --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        🎯 Tujuan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="scope" value="division" x-model="selScope" class="peer sr-only" checked>
                            <div class="p-3 rounded-xl border-2 border-slate-200 dark:border-slate-700
                                        peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-slate-800 transition-all">
                                <p class="font-semibold text-sm">🏢 Divisi Saya</p>
                                <p class="text-[11px] text-slate-500">Laporan khusus divisi</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="scope" value="company" x-model="selScope" class="peer sr-only">
                            <div class="p-3 rounded-xl border-2 border-slate-200 dark:border-slate-700
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-slate-800 transition-all">
                                <p class="font-semibold text-sm">🏛️ Perusahaan</p>
                                <p class="text-[11px] text-slate-500">Untuk manajemen</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Prioritas --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">⚡ Prioritas</label>
                    <select name="priority" class="input w-full">
                        <option value="normal">Normal</option>
                        <option value="low">Low</option>
                        <option value="high">High - Penting</option>
                        <option value="urgent">Urgent - Segera</option>
                    </select>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        📝 Deskripsi Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="6" required class="input w-full"
                              placeholder="Jelaskan secara detail isi laporan Anda..."></textarea>
                </div>
            </form>
        </div>

        {{-- Footer --}}
        <div class="p-5 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 shrink-0">
            <button type="button" @click="showCreateModal = false"
                    class="px-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-slate-100 transition">
                Batal
            </button>
            <button type="submit" form="karyawanReportForm"
                    class="px-5 py-2 text-sm rounded-lg font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white hover:opacity-90 shadow-md transition">
                📤 Kirim Laporan
            </button>
        </div>
    </div>
</div>
