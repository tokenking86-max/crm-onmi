@extends('crm.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-500">Resumen de ventas y actividad reciente</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Leads Totales</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_leads'] }}</p>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-400">{{ $stats['new_leads'] }} nuevos sin contactar</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pipeline Abierto</p>
                    <p class="text-2xl font-bold text-gray-900">S/ {{ number_format($stats['total_pipeline_value'], 0) }}</p>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-400">Ponderado: S/ {{ number_format($stats['weighted_pipeline'], 0) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Cotizaciones Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_quotes'] }}</p>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-400">Valor: S/ {{ number_format($stats['total_quotes_value'], 0) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Ganados</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['won_opportunities'] }}</p>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-400">S/ {{ number_format($stats['won_value'], 0) }} facturados</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pipeline Visual -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Pipeline por Etapa</h2>
            <div class="space-y-3">
                @forelse($pipeline as $stage)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $stage->color }}"></div>
                            <span class="text-sm font-medium text-gray-700">{{ $stage->name }}</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500">{{ $stage->opportunities_count }} deals</span>
                            <span class="text-sm font-semibold text-gray-900">S/ {{ number_format($stage->opportunities_sum_amount ?? 0, 0) }}</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full" style="width: {{ $stats['total_pipeline_value'] > 0 ? (($stage->opportunities_sum_amount ?? 0) / $stats['total_pipeline_value'] * 100) : 0 }}%; background-color: {{ $stage->color }}"></div>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">Sin datos de pipeline</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Opportunities -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Oportunidades Recientes</h2>
            <div class="space-y-4">
                @forelse($recentOpportunities as $opp)
                    <a href="{{ route('crm.opportunities.show', $opp) }}" class="block p-3 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $opp->title }}</p>
                                <p class="text-sm text-gray-500">{{ $opp->client->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">S/ {{ number_format($opp->amount, 0) }}</p>
                                <span class="inline-block px-2 py-1 text-xs rounded-full" style="background-color: {{ $opp->stage->color }}20; color: {{ $opp->stage->color }}">{{ $opp->stage->name }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-400 text-sm">Sin oportunidades recientes</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Upcoming Activities -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Próximas Actividades</h2>
        <div class="space-y-3">
            @forelse($upcomingActivities as $activity)
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            @switch($activity->type)
                                @case('call') <span class="text-indigo-600">&#128222;</span> @break
                                @case('email') <span class="text-indigo-600">&#9993;</span> @break
                                @case('meeting') <span class="text-indigo-600">&#128197;</span> @break
                                @case('whatsapp') <span class="text-indigo-600">&#128172;</span> @break
                                @default <span class="text-indigo-600">&#128203;</span>
                            @endswitch
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $activity->subject }}</p>
                            <p class="text-sm text-gray-500">{{ $activity->description ?? 'Sin referencia' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $activity->due_date->format('d/m/Y H:i') }}</p>
                        <form method="POST" action="{{ route('crm.activities.complete', $activity) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-green-600 hover:underline">Completar</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-sm">Sin actividades pendientes</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
