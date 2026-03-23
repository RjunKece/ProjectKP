<div id="editUserModal"
     class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm
            flex items-center justify-center z-50 px-4">

    <div class="modal-card w-full max-w-lg p-7">

        {{-- HEADER --}}
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-slate-900">
                Edit User
            </h3>
            <p class="text-sm text-slate-500 mt-1">
                Perbarui informasi akun pengguna
            </p>
        </div>

        {{-- FORM --}}
        <div class="space-y-4">

            <div>
                <label class="label">Full Name</label>
                <input
                    class="input w-full"
                    value="Super Admin">
            </div>

            <div>
                <label class="label">Email Address</label>
                <input
                    type="email"
                    class="input w-full"
                    value="superadmin@erp.test">
            </div>

        </div>

        {{-- ACTION --}}
        <div class="flex items-center justify-end gap-4 mt-8">
            <button
                onclick="closeModal('editUserModal')"
                class="text-sm text-slate-500 hover:text-slate-700">
                Cancel
            </button>

            <button
                class="px-5 py-2 rounded-lg text-sm font-semibold
                       bg-[#d4af37] text-white
                       hover:opacity-90 transition">
                Update User
            </button>
        </div>

    </div>
</div>
