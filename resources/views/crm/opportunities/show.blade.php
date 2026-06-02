@extends('crm.layouts.app')

@section('title', 'Oportunidad: ' . $opportunity->title)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $opportunity->title }}</h1>
            <p class="text-gray-500">{{ $opportunity->client->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('crm.quotes.create', ['opportunity_id' => $opportunity->id, 'client_id' => $opportunity->client_id]) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition font-medium">+ Cotización</a>
            <a href="{{ route('crm.opportunities.edit', $opportunity) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">Editar</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Opportunity Info -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Detalles</h2>
            <dl class="space-y-3 text-sm">
                <div><dt class="text-gray-500">Etapa</dt><dd><span class="px-2 py-1 text-xs rounded-full" style="background-color: {{ $opportunity->stage->color }}20; color: {{ $opportunity->stage->color }}">{{ $opportunity->stage->name }}</span></dd></div>
                <div><dt class="text-gray-500">Monto</dt><dd class="text-lg font-bold">S/ {{ number_format($opportunity->amount, 2) }}</dd></div>
                <div><dt class="text-gray-500">Probabilidad</dt><dd>{{ $opportunity->probability }}%</dd></div>
                <div><dt class="text-gray-500">Valor Ponderado</dt><dd class="font-semibold">S/ {{ number_format($opportunity->weighted_amount, 2) }}</dd></div>
                <div><dt class="text-gray-500">Cierre Estimado</dt><dd>{{ $opportunity->expected_close_date?->format('d/m/Y') ?? '-' }}</dd></div>
                @if($opportunity->actual_close_date)
                    <div><dt class="text-gray-500">Cierre Real</dt><dd>{{ $opportunity->actual_close_date->format('d/m/Y') }}</dd></div>
                @endif
                <div><dt class="text-gray-500">Asignado a</dt><dd>{{ $opportunity->assignee?->name ?? '-' }}</dd></div>
                @if($opportunity->description)
                    <div><dt class="text-gray-500">Descripción</dt><dd>{{ $opportunity->description }}</dd></div>
                @endif
            </dl>
        </div>

        <!-- Quotes -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Cotizaciones</h2>
            </div>
            <div class="space-y-3">
                @forelse($opportunity->quotes as $quote)
                    <a href="{{ route('crm.quotes.show', $quote) }}" class="block p-3 rounded-lg hover:bg-gray-50 border border-gray-100">
                        <div class="flex justify-between">
                            <span class="font-mono text-sm text-gray-600">{{ $quote->number }}</span>
                            @switch($quote->status)
                                @case('draft') <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">Borrador</span> @break
                                @case('sent') <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-600">Enviada</span> @break
                                @case('approved') <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-600">Aprobada</span> @break
                                @case('rejected') <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-600">Rechazada</span> @break
                            @endswitch
                        </div>
                        <p class="font-semibold mt-1">S/ {{ number_format($quote->total, 2) }}</p>
                    </a>
                @empty
                    <p class="text-gray-400 text-sm text-center py-4">Sin cotizaciones</p>
                @endforelse
            </div>
        </div>

        <!-- Activities -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Actividades</h2>
            <form method="POST" action="{{ route('crm.activities.store') }}" class="mb-4 p-3 bg-gray-50 rounded-lg space-y-2">
                @csrf
                <input type="hidden" name="activityable_type" value="opportunity">
                <input type="hidden" name="activityable_id" value="{{ $opportunity->id }}">
                <input type="text" name="subject" placeholder="Asunto" required class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
                <div class="flex space-x-2">
                    <select name="type" class="flex-1 border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
                        <option value="note">Nota</option>
                        <option value="call">Llamada</option>
                        <option value="email">Email</option>
                        <option value="meeting">Reunión</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm">+</button>
                </div>
            </form>
            <div class="space-y-2">
                @forelse($opportunity->activities->take(5) as $activity)
                    <div class="p-2 text-sm {{ $activity->is_completed ? 'text-gray-400 line-through' : '' }}">
                        <p class="font-medium">{{ $activity->subject }}</p>
                        <p class="text-xs text-gray-400">{{ ucfirst($activity->type) }} | {{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm text-center py-4">Sin actividades</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
