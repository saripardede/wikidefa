<!-- resources/views/admin/partials/confirmation-modal.blade.php -->
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div id="modalBox" class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md scale-95 opacity-0 transform transition-all duration-300">
        <h3 class="text-lg font-semibold mb-4">Ubah Status</h3>
        <p class="mb-6" id="confirmationText">Ingin mengubah status persetujuan tutorial?</p>
        <div class="flex flex-col gap-2">
            <form id="confirmationForm" method="POST">
                @csrf
                <input type="hidden" name="status" id="statusField">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Revisi</button>
            </form>
            <button onclick="submitStatus('disetujui')" class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Disetujui</button>
            <button onclick="submitStatus('ditolak')" class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Ditolak</button>
            <button onclick="closeModal()" class="w-full bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400 mt-2">Tutup</button>
        </div>
    </div>
</div>
