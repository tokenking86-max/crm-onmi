@extends('crm.layouts.app')

@section('title', 'Oportunidades')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Oportunidades</h1>
            <p class="text-gray-500">Gestiona tus oportunidades de venta</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('crm.opportunities.pipeline') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition font-medium">
                Pipeline
            </a>
            <a href="{{ route('crm.opportunities.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
                + Nueva
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Etapa</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Monto</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Prob.</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ponderado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Cierre</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($opportunities as $opp)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <a href="{{ route('crm.opportunities.show', $opp) }}" class="font-medium text-indigo-600 hover:text-indigo-800">{{ $opp->title }}</a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $opp->client->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full" style="background-color: {{ $opp->stage->color }}20; color: {{ $opp->stage->color }}">{{ $opp->stage->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-right font-medium">S/ {{ number_format($opp->amount, 0) }}</td>
                        <td class="px-6 py-4 text-sm text-right text-gray-500">{{ $opp->probability }}%</td>
                        <td class="px-6 py-4 text-sm text-right font-medium">S/ {{ number_format($opp->weighted_amount, 0) }}</td>
                        <td class="px-6 py-4 text-sm text-right text-gray-500">{{ $opp->expected_close_date?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No hay oportunidades</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $opportunities->withQueryString()->links() }}
</div>
@endsection
