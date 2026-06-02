@extends('crm.layouts.app')

@section('title', 'Pipeline de Ventas')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pipeline de Ventas</h1>
            <p class="text-gray-500">Vista Kanban de todas las oportunidades</p>
        </div>
        <a href="{{ route('crm.opportunities.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
            + Nueva Oportunidad
        </a>
    </div>

    <!-- Pipeline Board -->
    <div class="flex space-x-4 overflow-x-auto pb-4" style="min-height: 500px;">
        @forelse($stages as $stage)
            <div class="flex-shrink-0 w-72 bg-gray-100 rounded-xl p-3">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $stage->color }}"></div>
                        <h3 class="font-semibold text-gray-700 text-sm">{{ $stage->name }}</h3>
                    </div>
                    <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded-full">{{ $stage->opportunities->count() }}</span>
                </div>

                <div class="space-y-3">
                    @forelse($stage->opportunities as $opp)
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-100 cursor-pointer hover:shadow-md transition"
                             x-data="{ showMenu: false }"
                             @click.outside="showMenu = false">
                            <a href="{{ route('crm.opportunities.show', $opp) }}">
                                <h4 class="font-medium text-gray-900 text-sm">{{ $opp->title }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $opp->client->name }}</p>
                                <div class="flex items-center justify-between mt-3">
                                    <span class="font-semibold text-gray-900">S/ {{ number_format($opp->amount, 0) }}</span>
                                    <span class="text-xs text-gray-400">{{ $opp->probability }}%</span>
                                </div>
                                @if($opp->expected_close_date)
                                    <p class="text-xs text-gray-400 mt-1">Cierre: {{ $opp->expected_close_date->format('d/m/Y') }}</p>
                                @endif
                            </a>
                            @if(!$stage->is_won && !$stage->is_lost)
                                <div class="mt-3 flex flex-wrap gap-1">
                                    @foreach($stages as $s)
                                        @if($s->id !== $stage->id && !$s->is_won && !$s->is_lost)
                                            <form method="POST" action="{{ route('crm.opportunities.update-stage', $opp) }}">
                                                @csrf
                                                <input type="hidden" name="stage_id" value="{{ $s->id }}">
                                                <button type="submit" class="text-xs px-2 py-1 rounded border border-gray-200 hover:bg-gray-50 transition" title="Mover a {{ $s->name }}">
                                                    <span class="inline-block w-2 h-2 rounded-full" style="background-color: {{ $s->color }}"></span>
                                                </button>
                                            </form>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 text-sm">
                            Sin oportunidades
                        </div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-400">No hay etapas configuradas</div>
        @endforelse
    </div>
</div>
@endsection
