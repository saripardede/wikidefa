<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-indigo-900 text-white h-screen p-4">
            <h1 class="text-3xl font-bold mb-6">WikiDefa</h1>
            <p class="mb-4">Josmar<br><span class="text-gray-300">Admin</span></p>
            <ul>
                <li class="mb-2"><a href="#" class="flex items-center gap-2"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="mb-2"><a href="#" class="flex items-center gap-2"><i class="fas fa-users"></i> Manajemen Pengguna</a></li>
                <li class="mb-2"><a href="#" class="flex items-center gap-2"><i class="fas fa-check-circle"></i> Persetujuan Tutorial</a></li>
                <li class="mb-2"><a href="#" class="flex items-center gap-2"><i class="fas fa-star"></i> Sistem Reward</a></li>
            </ul>

            <!-- Tombol Logout dengan Form POST -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 text-white py-2 px-4 mt-6 w-full">Logout</button>
            </form>
        </div>

        <!-- Content -->
        <div class="flex-1 p-6">
            <h2 class="text-2xl font-semibold mb-4">Manajemen Pengguna</h2>
            <div class="flex justify-between items-center mb-4">
                <select class="border p-2">
                    <option>Semua Status</option>
                    <option>Aktif</option>
                    <option>Pending</option>
                </select>
                <input type="text" placeholder="Cari Pengguna..." class="border p-2">
            </div>
            <table class="bg-white shadow-md rounded-lg w-full">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="p-3">Username</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Nomor HP</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Tanggal Registrasi</th>
                        <th class="p-3">Last Login</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="p-3">Ahmad</td>
                        <td class="p-3">ahmad@telkom.com</td>
                        <td class="p-3">0812345</td>
                        <td class="p-3">Teknisi</td>
                        <td class="p-3 text-green-500">Aktif</td>
                        <td class="p-3">2025-02-01</td>
                        <td class="p-3">2025-02-15</td>
                        <td class="p-3">
                            <button class="bg-red-500 text-white p-1 px-2 rounded">Hapus</button>
                            <button class="bg-blue-500 text-white p-1 px-2 rounded">Edit</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="mt-2 text-gray-500">Menampilkan 1 dari 3 pengguna</p>
        </div>
    </div>
</body>
</html>
