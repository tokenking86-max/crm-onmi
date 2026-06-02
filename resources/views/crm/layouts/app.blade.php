<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CRM') - Ommi Solutions</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-indigo-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('crm.dashboard') }}" class="font-bold text-xl">Ommi CRM</a>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('crm.dashboard') }}" class="hover:text-indigo-200 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('crm.dashboard') ? 'bg-indigo-700' : '' }}">Dashboard</a>
                        <a href="{{ route('crm.leads.index') }}" class="hover:text-indigo-200 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('crm.leads.*') ? 'bg-indigo-700' : '' }}">Leads</a>
                        <a href="{{ route('crm.clients.index') }}" class="hover:text-indigo-200 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('crm.clients.*') ? 'bg-indigo-700' : '' }}">Clientes</a>
                        <a href="{{ route('crm.opportunities.pipeline') }}" class="hover:text-indigo-200 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('crm.opportunities.*') ? 'bg-indigo-700' : '' }}">Pipeline</a>
                        <a href="{{ route('crm.quotes.index') }}" class="hover:text-indigo-200 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('crm.quotes.*') ? 'bg-indigo-700' : '' }}">Cotizaciones</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-indigo-200">{{ auth()->user()->name ?? 'Invitado' }}</span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
