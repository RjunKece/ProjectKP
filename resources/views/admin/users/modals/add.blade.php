<div id="addUserModal"
     class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm
            flex items-center justify-center z-50 px-4">

    <div class="w-full max-w-lg rounded-2xl p-7
                bg-white dark:bg-slate-900
                border border-slate-200 dark:border-slate-700
                shadow-xl">

        <!-- HEADER -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold
                       text-slate-900 dark:text-white">
                Add New User
            </h3>

            <p class="text-sm mt-1
                      text-slate-500 dark:text-slate-400">
                Buat akun pengguna sesuai role dan struktur organisasi
            </p>
        </div>

        <!-- FORM -->
        <div class="space-y-4">

            <!-- NAME -->
            <div>
                <label class="block text-sm font-medium mb-1
                              text-slate-700 dark:text-slate-300">
                    Full Name
                </label>

                <input
                    type="text"
                    class="w-full px-4 py-2.5 rounded-lg
                           bg-white dark:bg-slate-800
                           border border-slate-300 dark:border-slate-600
                           text-slate-900 dark:text-white
                           placeholder:text-slate-400
                           focus:ring-2 focus:ring-[#d4af37]
                           focus:border-[#d4af37]
                           outline-none transition"
                    placeholder="Nama lengkap pengguna">
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block text-sm font-medium mb-1
                              text-slate-700 dark:text-slate-300">
                    Email Address
                </label>

                <input
                    type="email"
                    class="w-full px-4 py-2.5 rounded-lg
                           bg-white dark:bg-slate-800
                           border border-slate-300 dark:border-slate-600
                           text-slate-900 dark:text-white
                           placeholder:text-slate-400
                           focus:ring-2 focus:ring-[#d4af37]
                           focus:border-[#d4af37]
                           outline-none transition"
                    placeholder="email@company.com">
            </div>

            <!-- ROLE -->
            <div>
                <label class="block text-sm font-medium mb-1
                              text-slate-700 dark:text-slate-300">
                    User Role
                </label>

                <select
                    onchange="handleRoleChange(this)"
                    class="w-full px-4 py-2.5 rounded-lg
                           bg-white dark:bg-slate-800
                           border border-slate-300 dark:border-slate-600
                           text-slate-900 dark:text-white
                           focus:ring-2 focus:ring-[#d4af37]
                           focus:border-[#d4af37]
                           outline-none transition">

                    <option value="">Select role</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="admin">Admin</option>
                    <option value="karyawan">Karyawan</option>

                </select>
            </div>

            <!-- DIVISION -->
            <div id="divisionField" class="hidden">
                <label class="block text-sm font-medium mb-1
                              text-slate-700 dark:text-slate-300">
                    Division
                </label>

                <select
                    id="divisionSelect"
                    class="w-full px-4 py-2.5 rounded-lg
                           bg-white dark:bg-slate-800
                           border border-slate-300 dark:border-slate-600
                           text-slate-900 dark:text-white
                           focus:ring-2 focus:ring-[#d4af37]
                           focus:border-[#d4af37]
                           outline-none transition">

                    <option value="">Select division</option>
                    <option>Marketing</option>
                    <option>Sales</option>
                    <option>Finance</option>

                </select>
            </div>

        </div>

        <!-- ACTION -->
        <div class="flex items-center justify-end gap-4 mt-8">

            <button
                onclick="closeModal('addUserModal')"
                class="text-sm
                       text-slate-500 dark:text-slate-400
                       hover:text-slate-700 dark:hover:text-white
                       transition">

                Cancel

            </button>

            <button
                class="px-5 py-2.5 rounded-lg text-sm font-semibold
                       bg-[#d4af37] text-white
                       hover:opacity-90
                       shadow-md shadow-[#d4af37]/20
                       transition">

                Save User

            </button>

        </div>

    </div>

</div>

<script>
function handleRoleChange(select) {
    const divisionField = document.getElementById('divisionField');
    const divisionSelect = document.getElementById('divisionSelect');

    if (select.value === 'karyawan') {
        divisionField.classList.remove('hidden');
    } else {
        divisionField.classList.add('hidden');
        if (divisionSelect) divisionSelect.value = '';
    }
}
</script>