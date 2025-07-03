<div 
    x-data="{ open: false }" 
    x-show="open"
    @open-status-modal.window="open = true"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    x-cloak
>
    <div 
        x-show="open"
        x-transition
        class="bg-white rounded-lg p-6 w-80 shadow-lg"
    >
        <h2 class="text-lg font-semibold mb-4 text-center">Ubah Status</h2>
        <p class="text-center text-sm mb-4">Ingin mengubah status persetujuan tutorial?</p>

        <form method="POST" :action="'/admin/tutorial-approval/{{ $tutorial->id }}/status'" x-ref="form">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" x-ref="statusInput">

            <div class="space-y-2">
                <button 
                    type="button"
                    @click="$refs.statusInput.value = 'revisi'; $refs.form.submit();"
                    class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600"
                >Revisi</button>

                <button 
                    type="button"
                    @click="$refs.statusInput.value = 'disetujui'; $refs.form.submit();"
                    class="w-full bg-gray-200 text-gray-700 py-2 rounded hover:bg-gray-300"
                >Disetujui</button>

                <button 
                    type="button"
                    @click="$refs.statusInput.value = 'ditolak'; $refs.form.submit();"
                    class="w-full bg-gray-200 text-gray-700 py-2 rounded hover:bg-gray-300"
                >Ditolak</button>
            </div>
        </form>

        <button 
            @click="open = false"
            class="mt-4 text-sm text-gray-500 hover:underline w-full text-center"
        >Batal</button>
    </div>
</div>
