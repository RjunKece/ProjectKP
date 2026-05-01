<div
    x-show="openReportModal"
    x-transition.opacity
    x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center"
>
    {{-- BACKDROP --}}
    <div
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        @click="openReportModal = false"
    ></div>

    {{-- ================= MODAL ================= --}}
    <div x-data="{ selectedScope: 'company' }"
        class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl
               w-full max-w-3xl
               max-h-[90vh] flex flex-col
               z-[10000]
               border border-slate-200 dark:border-slate-700"
        @click.stop
    >

        {{-- ================= HEADER ================= --}}
        <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex items-center gap-4 shrink-0">

            <div class="text-3xl">
                <span x-show="reportType === 'financial'">📊</span>
                <span x-show="reportType === 'department'">🏢</span>
                <span x-show="reportType === 'growth'">📈</span>
                <span x-show="reportType === 'custom'">🧩</span>
            </div>

            <div class="flex-1">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                    <span x-show="reportType === 'financial'">Financial Intelligence Report</span>
                    <span x-show="reportType === 'department'">Department Performance Report</span>
                    <span x-show="reportType === 'growth'">Growth & Trends Report</span>
                    <span x-show="reportType === 'custom'">Custom Intelligence Report</span>
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Buat laporan resmi untuk dokumentasi dan analisis internal
                </p>
            </div>

            <button @click="openReportModal = false"
                    class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                ✕
            </button>
        </div>

        {{-- ================= BODY (SCROLL) ================= --}}
        <div class="flex-1 overflow-y-auto px-6 py-6">

            <form
                method="POST"
                action="{{ route('admin.reports.store') }}"
                class="space-y-6"
                id="reportForm"
            >
                @csrf
                <input type="hidden" name="type" :value="reportType">

                {{-- JUDUL --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        Judul Laporan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required
                           class="input w-full"
                           placeholder="Contoh: Laporan Aktivitas Sales Bulanan">
                </div>

                {{-- SCOPE: Perusahaan atau Divisi --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        📌 Tujuan Laporan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="scope" value="company" x-model="selectedScope" class="peer sr-only" checked>
                            <div class="p-4 rounded-xl border-2 border-slate-200 dark:border-slate-700
                                        peer-checked:border-[#d4af37] peer-checked:bg-[#fffdf7] dark:peer-checked:bg-slate-800
                                        transition-all">
                                <div class="text-2xl mb-1">🏛️</div>
                                <p class="font-semibold text-sm text-slate-900 dark:text-white">Laporan Perusahaan</p>
                                <p class="text-xs text-slate-500 mt-0.5">Pengumuman & info penting untuk seluruh karyawan</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="scope" value="division" x-model="selectedScope" class="peer sr-only">
                            <div class="p-4 rounded-xl border-2 border-slate-200 dark:border-slate-700
                                        peer-checked:border-blue-500 peer-checked:bg-blue-50/50 dark:peer-checked:bg-slate-800
                                        transition-all">
                                <div class="text-2xl mb-1">🏢</div>
                                <p class="font-semibold text-sm text-slate-900 dark:text-white">Laporan Divisi</p>
                                <p class="text-xs text-slate-500 mt-0.5">Tugas & informasi khusus untuk divisi tertentu</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- PILIH DIVISI (hanya jika scope = division) --}}
                <div x-show="selectedScope === 'division'" x-transition>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        🏢 Pilih Divisi Tujuan <span class="text-red-500">*</span>
                    </label>
                    <select name="division_id" class="input w-full" :required="selectedScope === 'division'">
                        <option value="">-- Pilih Divisi --</option>
                        @foreach(\App\Models\Division::orderBy('nama_divisi')->get() as $div)
                            <option value="{{ $div->id }}">{{ $div->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- PRIORITAS --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        ⚡ Prioritas
                    </label>
                    <select name="priority" class="input w-full">
                        <option value="normal">Normal</option>
                        <option value="low">Low</option>
                        <option value="high">High - Penting</option>
                        <option value="urgent">Urgent - Segera</option>
                    </select>
                </div>

                {{-- TANGGAL --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        Tanggal Laporan <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="report_date" required
                           value="{{ date('Y-m-d') }}"
                           class="input w-full">
                </div>

                {{-- RINGKASAN --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        📌 Ringkasan Aktivitas
                    </label>
                    <textarea name="summary" rows="5"
                              class="input w-full"
                              placeholder="Jelaskan aktivitas utama, hasil kerja, dan poin penting..."></textarea>
                </div>

                {{-- ANALISIS --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        🧠 Analisis & Catatan
                    </label>
                    <textarea name="analysis" rows="4"
                              class="input w-full"
                              placeholder="Kendala, evaluasi, insight, atau rekomendasi..."></textarea>
                </div>

            </form>
        </div>

        {{-- ================= FOOTER ================= --}}
        <div class="p-5 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 shrink-0">
            <button type="button" @click="openReportModal = false"
                    class="px-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-slate-600
                           hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                Cancel
            </button>

            <button type="submit" form="reportForm"
                    class="px-5 py-2 text-sm rounded-lg font-semibold
                           bg-gradient-to-r from-[#f5e6b3] to-[#d4af37] text-slate-900
                           hover:opacity-90 shadow-md shadow-[#d4af37]/20 transition">
                💾 Simpan Laporan
            </button>
        </div>

    </div>
</div>
