<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Pak Budi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <a href="/products" class="text-xl font-bold text-blue-600">🛍️ Toko Pak Budi</a>
        <div class="flex gap-4 items-center">
            @auth
                <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-blue-600">🛒 Cart</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-gray-600 hover:text-red-500">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Register</a>
            @endauth
        </div>
    </nav>

    <!-- Content -->
    <main class="max-w-6xl mx-auto py-8 px-4">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
