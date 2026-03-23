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
    <div
        class="relative bg-white rounded-3xl shadow-2xl
               w-full max-w-4xl
               max-h-[90vh]        {{-- 🔥 BATAS TINGGI --}}
               flex flex-col       {{-- 🔥 WAJIB --}}
               z-[10000]"
        @click.stop
    >

        {{-- ================= HEADER (FIXED) ================= --}}
        <div class="p-8 border-b border-[#f5e6b3] flex items-center gap-5">

            <div class="text-4xl">
                <span x-show="reportType === 'financial'">📊</span>
                <span x-show="reportType === 'department'">🏢</span>
                <span x-show="reportType === 'growth'">📈</span>
                <span x-show="reportType === 'custom'">🧩</span>
            </div>

            <div class="flex-1">
                <h2 class="text-2xl font-semibold text-slate-900 leading-tight">
                    <span x-show="reportType === 'financial'">Financial Intelligence Report</span>
                    <span x-show="reportType === 'department'">Department Performance Report</span>
                    <span x-show="reportType === 'growth'">Growth & Trends Report</span>
                    <span x-show="reportType === 'custom'">Custom Intelligence Report</span>
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Form laporan resmi untuk dokumentasi dan analisis internal ERP
                </p>
            </div>
        </div>

        {{-- ================= BODY (SCROLL AREA) ================= --}}
        <div class="flex-1 overflow-y-auto px-8 py-6">

            <form
                method="POST"
                action="{{ route('admin.reports.generate') }}"
                enctype="multipart/form-data"
                class="space-y-8"
                id="reportForm"
            >
                @csrf

                <input type="hidden" name="type" :value="reportType">

                {{-- ================= JUDUL LAPORAN ================= --}}
                <div class="text-center space-y-3">
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
                        FORM LAPORAN AKTIVITAS
                    </h1>

                    <div class="flex justify-center items-center gap-3">
                        <span class="h-[2px] w-14 bg-[#f5e6b3]"></span>
                        <span class="h-[3px] w-24 bg-[#d4af37] rounded-full"></span>
                        <span class="h-[2px] w-14 bg-[#f5e6b3]"></span>
                    </div>

                    <p class="text-sm text-slate-500 max-w-xl mx-auto">
                        Digunakan untuk mencatat aktivitas kerja, capaian harian,
                        serta analisis kinerja sebagai bahan evaluasi internal perusahaan.
                    </p>
                </div>

                {{-- ================= NAMA LAPORAN ================= --}}
                <div class="space-y-2">
                    <label class="label">
                        Nama / Judul Laporan <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        required
                        class="input"
                        placeholder="Contoh: Laporan Aktivitas Sales Bulanan"
                    >
                </div>

                {{-- ================= INFORMASI PELAPOR ================= --}}
                <div class="box">
                    <h3 class="section-title">👤 Informasi Pelapor</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <input class="input" name="first_name" placeholder="Nama Depan">
                        <input class="input" name="last_name" placeholder="Nama Belakang">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <input class="input" name="position" placeholder="Jabatan">
                        <select class="input" name="department">
                            <option value="">Pilih Departemen</option>
                            <option>Sales</option>
                            <option>Marketing</option>
                            <option>Gudang</option>
                            <option>Keuangan</option>
                        </select>
                    </div>
                </div>

                {{-- ================= PERIODE ================= --}}
                <div class="box">
                    <h3 class="section-title">📅 Periode Laporan</h3>

                    <div class="grid grid-cols-3 gap-4">
                        <input type="date" name="report_date" class="input">
                        <input type="time" name="start_time" class="input">
                        <input type="time" name="end_time" class="input">
                    </div>
                </div>

                {{-- ================= AKTIVITAS ================= --}}
                <div class="box highlight">
                    <h3 class="section-title">📌 Ringkasan Aktivitas Utama</h3>

                    <textarea
                        name="summary"
                        rows="6"
                        class="input"
                        placeholder="Jelaskan aktivitas utama, hasil kerja, dan poin penting secara terstruktur..."
                    ></textarea>

                    <div class="grid grid-cols-2 gap-4">
                        <input class="input" name="kpi" placeholder="Target / KPI Harian">
                        <select class="input" name="status">
                            <option>Selesai</option>
                            <option>Dalam Proses</option>
                            <option>Tertunda</option>
                        </select>
                    </div>
                </div>

                {{-- ================= ANALISIS ================= --}}
                <div class="box">
                    <h3 class="section-title">🧠 Analisis & Catatan Tambahan</h3>

                    <textarea
                        name="analysis"
                        rows="5"
                        class="input"
                        placeholder="Tuliskan kendala, evaluasi, insight, atau rekomendasi..."
                    ></textarea>
                </div>

                {{-- ================= LAMPIRAN ================= --}}
                <div class="box dashed">
                    <label class="label block mb-2">
                        📎 Lampiran Pendukung (Opsional)
                    </label>
                    <input type="file" name="attachment" class="text-sm">
                </div>
            </form>
        </div>

        {{-- ================= FOOTER (FIXED) ================= --}}
        <div class="p-6 border-t bg-white flex justify-end gap-4">
            <button
                type="button"
                @click="openReportModal = false"
                class="btn-secondary"
            >
                Cancel
            </button>

            <button
                type="submit"
                form="reportForm"
                class="btn-primary"
            >
                Generate Report
            </button>
        </div>

    </div>
</div>
