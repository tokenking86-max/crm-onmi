@extends('crm.layouts.app')

@section('title', 'Lead: ' . $lead->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $lead->name }}</h1>
            <p class="text-gray-500">{{ $lead->company ?? 'Sin empresa' }}</p>
        </div>
        <div class="flex space-x-3">
            @if($lead->status !== 'qualified')
                <form method="POST" action="{{ route('crm.leads.convert', $lead) }}" onsubmit="return confirm('¿Convertir este lead a cliente?')">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium">Convertir a Cliente</button>
                </form>
            @endif
            <a href="{{ route('crm.leads.edit', $lead) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">Editar</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Lead Info -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Información</h2>
            <dl class="space-y-3">
                <div><dt class="text-sm text-gray-500">Estado</dt><dd class="font-medium">
                    @switch($lead->status)
                        @case('new') <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">Nuevo</span> @break
                        @case('contacted') <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Contactado</span> @break
                        @case('qualified') <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Calificado</span> @break
                        @case('unqualified') <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">No calificado</span> @break
                    @endswitch
                </dd></div>
                <div><dt class="text-sm text-gray-500">Email</dt><dd>{{ $lead->email ?? '-' }}</dd></div>
                <div><dt class="text-sm text-gray-500">Teléfono</dt><dd>{{ $lead->phone ?? '-' }}</dd></div>
                <div><dt class="text-sm text-gray-500">Fuente</dt><dd>{{ ucfirst(str_replace('_', ' ', $lead->source ?? '-')) }}</dd></div>
                <div><dt class="text-sm text-gray-500">Valor Estimado</dt><dd class="font-semibold text-lg">{{ $lead->estimated_value ? 'S/ ' . number_format($lead->estimated_value, 0) : '-' }}</dd></div>
                <div><dt class="text-sm text-gray-500">Asignado a</dt><dd>{{ $lead->assignee?->name ?? '-' }}</dd></div>
                @if($lead->notes)
                    <div><dt class="text-sm text-gray-500">Notas</dt><dd class="text-sm">{{ $lead->notes }}</dd></div>
                @endif
            </dl>
        </div>

        <!-- Activities -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Actividades</h2>
            </div>

            <!-- Add Activity Form -->
            <form method="POST" action="{{ route('crm.activities.store') }}" class="mb-6 p-4 bg-gray-50 rounded-lg space-y-3">
                @csrf
                <input type="hidden" name="activityable_type" value="lead">
                <input type="hidden" name="activityable_id" value="{{ $lead->id }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <input type="text" name="subject" placeholder="Asunto" required class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                    <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="note">Nota</option>
                        <option value="call">Llamada</option>
                        <option value="email">Email</option>
                        <option value="meeting">Reunión</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                    <input type="datetime-local" name="due_date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <textarea name="description" rows="2" placeholder="Descripción (opcional)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500"></textarea>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">Agregar Actividad</button>
            </form>

            <div class="space-y-3">
                @forelse($lead->activities as $activity)
                    <div class="flex items-start justify-between p-3 rounded-lg {{ $activity->is_completed ? 'bg-green-50' : 'bg-white border border-gray-100' }}">
                        <div class="flex items-start space-x-3">
                            <div class="mt-1">
                                @if($activity->is_completed)
                                    <span class="text-green-500">&#10003;</span>
                                @else
                                    <span class="text-gray-400">&#9679;</span>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 {{ $activity->is_completed ? 'line-through text-gray-500' : '' }}">{{ $activity->subject }}</p>
                                <p class="text-xs text-gray-400">{{ ucfirst($activity->type) }} | {{ $activity->created_at->format('d/m/Y H:i') }}</p>
                                @if($activity->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ $activity->description }}</p>
                                @endif
                            </div>
                        </div>
                        @if(!$activity->is_completed)
                            <form method="POST" action="{{ route('crm.activities.complete', $activity) }}">
                                @csrf
                                <button type="submit" class="text-xs text-green-600 hover:underline">Completar</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-400 text-sm text-center py-4">Sin actividades registradas</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
