<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WikiDefa</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Custom Style -->
    <style>
        body { font-family: Arial, sans-serif; }
        .sidebar { background-color: #1F2937; color: white; height: 100vh; }
        .sidebar h1 { font-size: 1.875rem; font-weight: bold; margin-bottom: 1.5rem; color: #E11D48; }
        .sidebar ul li a {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            margin-left: -0.8rem;
            color: white;
            margin-bottom: 0.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s;
        }
        .sidebar ul li a:hover { background-color: #374151; }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <header class="bg-gray-800 p-4 flex justify-end items-center">
        <div class="flex items-center space-x-4">

            <!-- Form Pencarian -->
            <form method="GET" action="{{ route('search.tutorial') }}" class="flex items-center space-x-2">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari tutorial..." 
                    class="border px-4 py-2 rounded w-64"
                >
            </form>

            <!-- Icon Profil -->
            <i onclick="toggleProfileModal()" class="fas fa-user-circle text-2xl text-white cursor-pointer"></i>

            <!-- Modal Profil -->
            <div id="profileModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
                <div class="bg-[#182235] w-96 p-6 rounded-xl text-white relative">

                    <!-- Tombol Close -->
                    <button onclick="toggleProfileModal()" class="absolute top-3 right-4 text-red-500 text-xl font-bold">✕</button>

                    <!-- Avatar -->
                    <div class="flex justify-center mb-4">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-5xl text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Poin -->
                    <div class="flex justify-center items-center space-x-2 bg-gray-700 w-24 mx-auto py-1 px-3 rounded-full mb-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/1828/1828884.png" class="w-4 h-4" alt="coin">
                        <span class="text-sm font-semibold">{{ $points }}</span>
                    </div>

                    <!-- Info User -->
                    <div class="bg-white text-center text-gray-800 rounded-lg py-2 mb-3">
                        <p class="font-semibold">Username</p>
                        <p>{{ $user->name ?? '-' }}</p>
                    </div>

                    <div class="bg-white text-center text-gray-800 rounded-lg py-2 mb-3">
                        <p class="font-semibold">Tanggal Daftar</p>
                        <p>{{ \Carbon\Carbon::parse($user->created_at)->format('d F Y') }}</p>
                    </div>

                    <div class="bg-white text-center text-gray-800 rounded-lg py-2">
                        <p class="font-semibold">Terakhir Login</p>
                        <p>{{ \Carbon\Carbon::parse($user->last_login_at)->format('d F Y') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="sidebar fixed top-0 left-0 h-screen w-64 p-6 bg-[#1A2238] z-50">
            <h1 class="text-2xl font-bold mb-6"><i class="fas fa-book "></i> WikiDefa</h1>

            <!-- User Info -->
            <!-- <div class="flex items-center space-x-3 mb-6 -ml-2">
                <div class="p-2 rounded-full text-3xl text-[#1A2238]">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div>
                    <div class="text-white font-semibold text-base">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-blue-300">{{ Auth::user()->role }}</div>
                </div>
            </div> -->

            <!-- Menu -->
            <ul>
                <li>
                    <a href="{{ route('user.index') }}">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.information') }}" class="whitespace-nowrap">
                        <i class="fas fa-users"></i> Information
                    </a>
                </li>
                <li>
                    <a href="{{ route('tutorial.index') }}">
                        <i class="fas fa-file-alt"></i> Tutorial
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.contribution') }}">
                        <i class="fas fa-gift"></i> My Contribution
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.leaderboard') }}">
                        <i class="fas fa-gift"></i> Leaderboard
                    </a>
                </li>
            </ul>

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST" class="mt-96 ml-auto">
                @csrf
                <button type="submit" class="flex items-center justify-center space-x-2 w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 pr-[26rem] mt-6 ml-64 flex flex-col">
            @yield('content')
        </main>
    </div>

    <!-- Script Toggle Modal -->
    <script>
        function toggleProfileModal() {
            const modal = document.getElementById('profileModal');
            modal.classList.toggle('hidden');
        }
    </script>

</body>
</html>
