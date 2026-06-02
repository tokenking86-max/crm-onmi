@extends('crm.layouts.app')

@section('title', 'Nueva Oportunidad')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Nueva Oportunidad</h1>

    <form method="POST" action="{{ route('crm.opportunities.store') }}" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cliente *</label>
                <select name="client_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Seleccionar...</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', request('client_id')) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Etapa *</label>
                <select name="stage_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    @foreach($stages as $stage)
                        <option value="{{ $stage->id }}" {{ old('stage_id') == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Monto (S/) *</label>
                <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="0" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Probabilidad (%) *</label>
                <input type="number" name="probability" value="{{ old('probability', 50) }}" min="0" max="100" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Cierre Estimada</label>
                <input type="date" name="expected_close_date" value="{{ old('expected_close_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
            <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
        </div>
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('crm.opportunities.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">Crear Oportunidad</button>
        </div>
    </form>
</div>
@endsection
