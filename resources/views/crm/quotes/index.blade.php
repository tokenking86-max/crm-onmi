@extends('crm.layouts.app')

@section('title', 'Cotizaciones')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Cotizaciones</h1>
            <p class="text-gray-500">Gestiona las cotizaciones para tus clientes</p>
        </div>
        <a href="{{ route('crm.quotes.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
            + Nueva Cotización
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <form method="GET" class="flex gap-4 items-end">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por número o cliente..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Todos</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Borrador</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Enviada</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprobada</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazada</option>
                </select>
            </div>
            <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm">Filtrar</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Oportunidad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Válida hasta</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($quotes as $quote)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <a href="{{ route('crm.quotes.show', $quote) }}" class="font-mono text-sm font-medium text-indigo-600 hover:text-indigo-800">{{ $quote->number }}</a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $quote->client->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $quote->opportunity->title }}</td>
                        <td class="px-6 py-4">
                            @switch($quote->status)
                                @case('draft') <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">Borrador</span> @break
                                @case('sent') <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-600">Enviada</span> @break
                                @case('approved') <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-600">Aprobada</span> @break
                                @case('rejected') <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-600">Rechazada</span> @break
                                @case('expired') <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-600">Expirada</span> @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-sm text-right font-semibold">S/ {{ number_format($quote->total, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right text-gray-500">{{ $quote->valid_until?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No hay cotizaciones</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $quotes->withQueryString()->links() }}
</div>
@endsection
