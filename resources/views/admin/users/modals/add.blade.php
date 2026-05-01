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
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf

            <!-- NAME -->
            <div>
                <label class="block text-sm font-medium mb-1
                              text-slate-700 dark:text-slate-300">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" required
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
                <input type="email" name="email" required
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
                <select name="role_id" required
                    onchange="handleRoleChange(this)"
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
                <select name="division_id" id="divisionSelect" required
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

            <!-- PASSWORD INFO -->
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg p-3">
                <p class="text-xs text-amber-700 dark:text-amber-300">
                    🔐 Password default: <strong>password123</strong> — User wajib ganti setelah login pertama.
                </p>
            </div>

            <!-- ACTION -->
            <div class="flex items-center justify-end gap-4 mt-8">
                <button type="button"
                    onclick="closeModal('addUserModal')"
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
                    Save User
                </button>
            </div>
        </form>

    </div>
</div>