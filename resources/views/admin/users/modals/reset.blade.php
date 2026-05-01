<div id="resetModal"
     class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm
            flex items-center justify-center z-50"
     onclick="if(event.target===this) closeModal('resetModal')">

    <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 w-full max-w-sm
                shadow-xl border border-slate-200 dark:border-slate-700">

        {{-- ICON --}}
        <div class="mx-auto mb-4 w-12 h-12 flex items-center justify-center
                    rounded-full bg-[#d4af37]/20 text-[#d4af37] text-xl">
            🔐
        </div>

        {{-- TITLE --}}
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white text-center mb-1">
            Reset Password
        </h3>

        {{-- DESC --}}
        <p class="text-sm text-slate-500 text-center mb-6 leading-relaxed">
            Password user akan diatur ulang ke <strong>password123</strong>.
            User wajib login ulang setelah reset.
        </p>

        {{-- ACTION --}}
        <div class="flex justify-center gap-4">
            <button
                type="button"
                onclick="closeModal('resetModal')"
                class="px-4 py-2 text-sm text-slate-500
                       hover:text-slate-700 transition rounded-lg
                       border border-slate-200 dark:border-slate-700">
                Cancel
            </button>

            <form id="resetForm" method="POST" action="">
                @csrf
                @method('PUT')

                <button
                    type="submit"
                    class="px-5 py-2 text-sm font-medium rounded-lg
                           bg-[#d4af37] text-black
                           hover:bg-[#c9a635] transition shadow-sm">
                    Confirm Reset
                </button>
            </form>
        </div>
    </div>
</div>
