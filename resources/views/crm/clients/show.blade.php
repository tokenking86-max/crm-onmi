@extends('crm.layouts.app')

@section('title', 'Cliente: ' . $client->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $client->name }}</h1>
            <p class="text-gray-500">{{ $client->company ?? 'Sin empresa' }}</p>
        </div>
        <a href="{{ route('crm.clients.edit', $client) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">Editar</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Client Info -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Información</h2>
            <dl class="space-y-3 text-sm">
                <div><dt class="text-gray-500">Email</dt><dd>{{ $client->email ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Teléfono</dt><dd>{{ $client->phone ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Dirección</dt><dd>{{ $client->address ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Ciudad</dt><dd>{{ $client->city ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">País</dt><dd>{{ $client->country ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Asignado a</dt><dd>{{ $client->assignee?->name ?? '-' }}</dd></div>
                @if($client->notes)
                    <div><dt class="text-gray-500">Notas</dt><dd>{{ $client->notes }}</dd></div>
                @endif
            </dl>
        </div>

        <!-- Stats -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumen</h2>
            <div class="space-y-4">
                <div class="p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-600">Total Oportunidades</p>
                    <p class="text-2xl font-bold text-blue-900">S/ {{ number_format($totalOpportunities, 0) }}</p>
                </div>
                <div class="p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-green-600">Valor Ganado</p>
                    <p class="text-2xl font-bold text-green-900">S/ {{ number_format($wonValue, 0) }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Cotizaciones</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $client->quotes->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Opportunities -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Oportunidades</h2>
                <a href="{{ route('crm.opportunities.create', ['client_id' => $client->id]) }}" class="text-sm text-indigo-600 hover:underline">+ Nueva</a>
            </div>
            <div class="space-y-3">
                @forelse($client->opportunities as $opp)
                    <a href="{{ route('crm.opportunities.show', $opp) }}" class="block p-3 rounded-lg hover:bg-gray-50 border border-gray-100">
                        <p class="font-medium text-gray-900">{{ $opp->title }}</p>
                        <div class="flex justify-between mt-1">
                            <span class="px-2 py-1 text-xs rounded-full" style="background-color: {{ $opp->stage->color }}20; color: {{ $opp->stage->color }}">{{ $opp->stage->name }}</span>
                            <span class="text-sm font-semibold">S/ {{ number_format($opp->amount, 0) }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-400 text-sm text-center py-4">Sin oportunidades</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
