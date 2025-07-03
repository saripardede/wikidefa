<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>WikiDefa</title>
        @vite('resources/css/app.css')
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <style>
            body { font-family: Arial, sans-serif; }
            .sidebar { background-color: #1F2937; color: white; height: 100vh; }
            .sidebar h1 { font-size: 1.875rem; font-weight: bold; margin-bottom: 1.5rem; color: #E11D48; }
            .sidebar ul li a { display: flex; align-items: flex-start; gap: 0.5rem; padding: 0.5rem 1rem; margin-left: -0.8rem; color: white; margin-bottom: 0.5rem; border-radius: 0.375rem; transition: background-color 0.3s; }
            .sidebar ul li a:hover { background-color: #374151; }
        </style>
    </head>
    <body class="bg-gray-100">
        <div class="flex">
            <!-- Sidebar -->
            <div class="sidebar fixed top-0 left-0 h-screen w-64 p-6 text-white bg-[#1A2238]">
                <h1 class="text-2xl font-bold mb-6"><i class="fas fa-book"></i> WikiDefa</h1>
            
                <!-- User Info -->
                <div class="flex items-center space-x-3 mb-6 -ml-2">
                    <div class="p-2 rounded-full text-3xl text-[#1A2238]">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div>
                        <div class="text-white font-semibold text-base">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-blue-300">{{ Auth::user()->role }}</div>
                    </div>
                </div>
                <ul>
                    <li>
                        <a href="{{ route('admin.dashboard')}}">
                        <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.user-management') }}" class="whitespace-nowrap">
                        <i class="fas fa-users"></i> Manajemen Pengguna
                    </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tutorial-approval') }}">
                        <i class="fas fa-file-alt"></i> Persetujuan Tutorial
                    </a>
                    </li>
                    <li><a href="{{ route('admin.reward.index') }}">
                        <i class="fas fa-gift"></i> Sistem Reward
                    </a>
                    </li>
                </ul>
                <form action="{{ route('logout') }}" method="POST" class="mt-96 ml-auto text-white flex items-center space-x-2">
                    @csrf
                    <button type="submit" class="flex items-center justify-center space-x-2 w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>

            <!-- Main Content -->
            <div class="flex-1 p-6 pr-[26rem] mt-6 ml-64 flex flex-col">
                @yield('content')
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @stack('scripts')
        <script src="//unpkg.com/alpinejs" defer></script>
    </body>
    </html>
