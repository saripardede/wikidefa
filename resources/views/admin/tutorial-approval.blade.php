@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Persetujuan Tutorial</h2>

    <!-- Filter dan Pencarian -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <select id="statusFilter" class="border border-gray-300 rounded px-2 py-1 text-sm" onchange="applyFilters()">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="revision">Revision</option>
            </select>
            <input type="text" id="searchInput" placeholder="Cari tutorial..." class="border border-gray-300 rounded px-2 py-1 text-sm w-full sm:w-64" onkeyup="applyFilters()">
        </div>
    </div>

    <!-- Tabel Tutorial -->
    <div class="overflow-auto">
        <table class="min-w-full text-sm text-left border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Judul</th>
                    <th class="px-4 py-2 border">Penulis</th>
                    <th class="px-4 py-2 border">Kategori</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Tanggal Dibuat</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody id="tutorialTable">
                @forelse($tutorials as $tutorial)
                    <tr class="border-t tutorial-row">
                        <td class="px-4 py-2">{{ $tutorial->judul }}</td>
                        <td class="px-4 py-2">{{ $tutorial->user->name }}</td>
                        <td class="px-4 py-2">{{ $tutorial->kategori }}</td>
                        <td class="px-4 py-2">
                            @php
                                $statusColor = [
                                    'pending' => 'text-yellow-600',
                                    'approved' => 'text-green-600',
                                    'rejected' => 'text-red-600',
                                    'revision' => 'text-yellow-800',
                                ];
                            @endphp
                            <span class="{{ $statusColor[$tutorial->status] ?? 'text-gray-600' }}">
                                {{ ucfirst($tutorial->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $tutorial->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 space-x-1">
                            <a href="{{ route('admin.tutorial.detail', $tutorial->id) }}" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded text-sm">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr id="noResultsRow" class="hidden">
                        <td colspan="6" class="px-4 py-2 text-center text-sm text-gray-500">
                            Tidak ada tutorial yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const rows = document.querySelectorAll('#tutorialTable tr.tutorial-row');
    const noResultsRow = document.getElementById('noResultsRow');

    function applyFilters() {
        const selectedStatus = statusFilter.value.toLowerCase();
        const searchQuery = searchInput.value.toLowerCase();

        let visibleCount = 0;

        rows.forEach(row => {
            const titleCell = row.querySelector('td:nth-child(1)');
            const statusCell = row.querySelector('td:nth-child(4) span');

            if (!titleCell || !statusCell) return;

            const title = titleCell.textContent.toLowerCase();
            const status = statusCell.textContent.trim().toLowerCase();

            const matchesStatus = !selectedStatus || status === selectedStatus;
            const matchesSearch = title.includes(searchQuery);

            if (matchesStatus && matchesSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        noResultsRow.classList.toggle('hidden', visibleCount > 0);
    }

    statusFilter.addEventListener('change', applyFilters);
    searchInput.addEventListener('input', applyFilters);

    applyFilters(); // jalankan saat pertama kali
});
</script>

</div>

<!-- Modal Konfirmasi -->
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div id="modalBox" class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md scale-95 opacity-0 transform transition-all duration-300">
        <h3 class="text-lg font-semibold mb-4">Konfirmasi</h3>
        <p class="mb-4" id="confirmationText">Apakah Anda yakin?</p>

        <form id="confirmationForm" method="POST">
            @csrf
            <input type="hidden" name="status" id="statusField">

            <div id="pointInputContainer" class="mb-4 hidden">
                <label for="points" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Poin</label>
                <input type="number" name="points" id="points" min="0" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Ya</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const rows = document.querySelectorAll('#tutorialTable tr.tutorial-row');
    const noResultsRow = document.getElementById('noResultsRow');

    function applyFilters() {
        const selectedStatus = statusFilter.value.toLowerCase();
        const searchQuery = searchInput.value.toLowerCase();

        let visibleCount = 0;

        rows.forEach(row => {
            const titleCell = row.querySelector('td:nth-child(1)');
            const statusCell = row.querySelector('td:nth-child(4) span');

            if (!titleCell || !statusCell) return;

            const title = titleCell.textContent.toLowerCase();
            const status = statusCell.textContent.trim().toLowerCase();

            const matchesStatus = !selectedStatus || status === selectedStatus;
            const matchesSearch = title.includes(searchQuery);

            if (matchesStatus && matchesSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        noResultsRow.classList.toggle('hidden', visibleCount > 0);
    }

    statusFilter.addEventListener('change', applyFilters);
    searchInput.addEventListener('input', applyFilters);

    applyFilters();

    // Modal logic
    window.showModal = function (status, id) {
        const modal = document.getElementById('confirmationModal');
        const modalBox = document.getElementById('modalBox');
        const form = document.getElementById('confirmationForm');
        const statusField = document.getElementById('statusField');
        const confirmationText = document.getElementById('confirmationText');
        const pointContainer = document.getElementById('pointInputContainer');

        form.action = `/admin/tutorial-approval/${id}/status`;
        statusField.value = status;

        const messages = {
            approved: 'Apakah Anda yakin ingin menyetujui tutorial ini?',
            rejected: 'Apakah Anda yakin ingin menolak tutorial ini?',
            revision: 'Apakah Anda ingin meminta revisi pada tutorial ini?'
        };
        confirmationText.textContent = messages[status] || 'Apakah Anda yakin?';

        pointContainer.classList.toggle('hidden', status !== 'approved');

        modal.classList.remove('hidden');
        setTimeout(() => {
            modalBox.classList.remove('scale-95', 'opacity-0');
            modalBox.classList.add('scale-100', 'opacity-100');
        }, 10);
    };

    window.closeModal = function () {
        const modal = document.getElementById('confirmationModal');
        const modalBox = document.getElementById('modalBox');
        const form = document.getElementById('confirmationForm');

        form.reset();
        document.getElementById('pointInputContainer').classList.add('hidden');

        modalBox.classList.remove('scale-100', 'opacity-100');
        modalBox.classList.add('scale-95', 'opacity-0');

        setTimeout(() => modal.classList.add('hidden'), 300);
    };

    document.getElementById('confirmationForm').addEventListener('submit', function (e) {
        const status = document.getElementById('statusField').value;
        const points = document.getElementById('points').value;

        if (status === 'approved' && (!points || parseInt(points) < 0)) {
            e.preventDefault();
            alert('Jumlah poin harus diisi dengan benar.');
        }
    });
});
</script>
@endsection
