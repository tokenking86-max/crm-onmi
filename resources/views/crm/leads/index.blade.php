@extends('crm.layouts.app')

@section('title', 'Leads')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Leads</h1>
            <p class="text-gray-500">Gestiona tus prospectos de clientes</p>
        </div>
        <a href="{{ route('crm.leads.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
            + Nuevo Lead
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, empresa, email..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Nuevo</option>
                    <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contactado</option>
                    <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Calificado</option>
                    <option value="unqualified" {{ request('status') == 'unqualified' ? 'selected' : '' }}>No calificado</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fuente</label>
                <select name="source" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">Todas</option>
                    <option value="website" {{ request('source') == 'website' ? 'selected' : '' }}>Sitio Web</option>
                    <option value="referral" {{ request('source') == 'referral' ? 'selected' : '' }}>Referido</option>
                    <option value="cold_call" {{ request('source') == 'cold_call' ? 'selected' : '' }}>Llamada en frío</option>
                    <option value="social_media" {{ request('source') == 'social_media' ? 'selected' : '' }}>Redes Sociales</option>
                    <option value="event" {{ request('source') == 'event' ? 'selected' : '' }}>Evento</option>
                </select>
            </div>
            <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-medium">Filtrar</button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fuente</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Est.</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($leads as $lead)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <a href="{{ route('crm.leads.show', $lead) }}" class="font-medium text-indigo-600 hover:text-indigo-800">{{ $lead->name }}</a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $lead->company ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $lead->email ?? '' }}{{ $lead->email && $lead->phone ? ' | ' : '' }}{{ $lead->phone ?? '' }}
                            @if(!$lead->email && !$lead->phone) - @endif
                        </td>
                        <td class="px-6 py-4">
                            @switch($lead->status)
                                @case('new') <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">Nuevo</span> @break
                                @case('contacted') <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Contactado</span> @break
                                @case('qualified') <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Calificado</span> @break
                                @case('unqualified') <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">No calificado</span> @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $lead->source ?? '-')) }}</td>
                        <td class="px-6 py-4 text-sm text-right font-medium text-gray-900">{{ $lead->estimated_value ? 'S/ ' . number_format($lead->estimated_value, 0) : '-' }}</td>
                        <td class="px-6 py-4 text-right text-sm space-x-2">
                            <a href="{{ route('crm.leads.edit', $lead) }}" class="text-indigo-600 hover:text-indigo-800">Editar</a>
                            @if($lead->status !== 'qualified')
                                <form method="POST" action="{{ route('crm.leads.convert', $lead) }}" class="inline" onsubmit="return confirm('¿Convertir este lead a cliente?')">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800">Convertir</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No se encontraron leads</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $leads->withQueryString()->links() }}
</div>
@endsection
