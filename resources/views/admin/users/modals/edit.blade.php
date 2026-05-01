<div id="editUserModal"
     class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm
            flex items-center justify-center z-50 px-4"
     onclick="if(event.target===this) closeModal('editUserModal')">

    <div class="w-full max-w-lg rounded-2xl p-7
                bg-white dark:bg-slate-900
                border border-slate-200 dark:border-slate-700
                shadow-xl">

        <!-- HEADER -->
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Edit Data Karyawan
                </h3>
            </div>
            <p class="text-sm mt-1 text-slate-500 dark:text-slate-400">
                Perbarui informasi pengguna, role, dan divisi
            </p>
        </div>

        <!-- FORM -->
        <form id="editUserForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- NAME -->
            <div>
                <label class="block text-sm font-medium mb-1
                              text-slate-700 dark:text-slate-300">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="editUserName" required
                    class="w-full px-4 py-2.5 rounded-lg
                           bg-white dark:bg-slate-800
                           border border-slate-300 dark:border-slate-600
                           text-slate-900 dark:text-white
                           focus:ring-2 focus:ring-[#d4af37]
                           focus:border-[#d4af37]
                           outline-none transition"
                    placeholder="Nama lengkap pengguna">
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block text-sm font-medium mb-1
                              text-slate-700 dark:text-slate-300">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" id="editUserEmail" required
                    class="w-full px-4 py-2.5 rounded-lg
                           bg-white dark:bg-slate-800
                           border border-slate-300 dark:border-slate-600
                           text-slate-900 dark:text-white
                           focus:ring-2 focus:ring-[#d4af37]
                           focus:border-[#d4af37]
                           outline-none transition"
                    placeholder="email@company.com">
            </div>

            <!-- ROLE -->
            <div>
                <label class="block text-sm font-medium mb-1
                              text-slate-700 dark:text-slate-300">
                    User Role <span class="text-red-500">*</span>
                </label>
                <select name="role_id" id="editUserRole" required
                    onchange="handleEditRoleChange(this)"
                    class="w-full px-4 py-2.5 rounded-lg
                           bg-white dark:bg-slate-800
                           border border-slate-300 dark:border-slate-600
                           text-slate-900 dark:text-white
                           focus:ring-2 focus:ring-[#d4af37]
                           focus:border-[#d4af37]
                           outline-none transition">
                    <option value="">Select role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">
                            {{ ucfirst(str_replace('_', ' ', $role->nama_role)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- DIVISION -->
            <div>
                <label class="block text-sm font-medium mb-1
                              text-slate-700 dark:text-slate-300">
                    Division <span class="text-red-500">*</span>
                </label>
                <select name="division_id" id="editUserDivision" required
                    class="w-full px-4 py-2.5 rounded-lg
                           bg-white dark:bg-slate-800
                           border border-slate-300 dark:border-slate-600
                           text-slate-900 dark:text-white
                           focus:ring-2 focus:ring-[#d4af37]
                           focus:border-[#d4af37]
                           outline-none transition">
                    <option value="">Select division</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}">
                            {{ $division->nama_divisi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- ROLE CHANGE WARNING -->
            <div id="roleChangeWarning"
                 class="hidden bg-amber-50 dark:bg-amber-900/20
                        border border-amber-200 dark:border-amber-700
                        rounded-lg p-3 transition-all">
                <p class="text-xs text-amber-700 dark:text-amber-300 flex items-start gap-2">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <span>Mengubah role akan mempengaruhi hak akses pengguna ini di sistem. Pastikan perubahan sudah sesuai.</span>
                </p>
            </div>

            <!-- ACTION -->
            <div class="flex items-center justify-end gap-4 mt-8">
                <button type="button"
                    onclick="closeModal('editUserModal')"
                    class="text-sm text-slate-500 dark:text-slate-400
                           hover:text-slate-700 dark:hover:text-white transition">
                    Cancel
                </button>

                <button type="submit"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold
                           bg-gradient-to-r from-[#f5e6b3] to-[#d4af37] text-slate-900
                           hover:opacity-90
                           shadow-md shadow-[#d4af37]/20
                           transition">
                    Update Data
                </button>
            </div>
        </form>

    </div>
</div>
