<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ommi CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-indigo-600">Ommi CRM</h1>
            <p class="text-gray-500 mt-2">Inicia sesión para continuar</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="password" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Recordarme</label>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition font-medium">
                    Iniciar Sesión
                </button>
            </form>
        </div>
    </div>
</body>
</html>
