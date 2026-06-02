@extends('crm.layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Clientes</h1>
            <p class="text-gray-500">Directorio de clientes activos</p>
        </div>
        <a href="{{ route('crm.clients.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
            + Nuevo Cliente
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <form method="GET" class="flex gap-4 items-end">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, empresa, email..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-medium">Buscar</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($clients as $client)
            <a href="{{ route('crm.clients.show', $client) }}" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="text-indigo-600 font-bold text-lg">{{ substr($client->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $client->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $client->company ?? 'Sin empresa' }}</p>
                    </div>
                </div>
                <div class="mt-4 space-y-1 text-sm text-gray-500">
                    @if($client->email)<p>{{ $client->email }}</p>@endif
                    @if($client->phone)<p>{{ $client->phone }}</p>@endif
                    @if($client->city)<p>{{ $client->city }}, {{ $client->country }}</p>@endif
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between text-sm">
                    <span>{{ $client->opportunities_count ?? $client->opportunities->count() ?? 0 }} oportunidades</span>
                    <span>{{ $client->quotes_count ?? $client->quotes->count() ?? 0 }} cotizaciones</span>
                </div>
            </a>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-400">No se encontraron clientes</div>
        @endforelse
    </div>

    {{ $clients->withQueryString()->links() }}
</div>
@endsection
