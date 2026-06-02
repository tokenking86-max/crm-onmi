@extends('crm.layouts.app')

@section('title', 'Nuevo Lead')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Nuevo Lead</h1>

    <form method="POST" action="{{ route('crm.leads.store') }}" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
                <input type="text" name="company" value="{{ old('company') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fuente</label>
                <select name="source" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Seleccionar...</option>
                    <option value="website" {{ old('source') == 'website' ? 'selected' : '' }}>Sitio Web</option>
                    <option value="referral" {{ old('source') == 'referral' ? 'selected' : '' }}>Referido</option>
                    <option value="cold_call" {{ old('source') == 'cold_call' ? 'selected' : '' }}>Llamada en frío</option>
                    <option value="social_media" {{ old('source') == 'social_media' ? 'selected' : '' }}>Redes Sociales</option>
                    <option value="event" {{ old('source') == 'event' ? 'selected' : '' }}>Evento</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valor Estimado (S/)</label>
                <input type="number" name="estimated_value" value="{{ old('estimated_value') }}" step="0.01" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
            <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
        </div>
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('crm.leads.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">Guardar Lead</button>
        </div>
    </form>
</div>
@endsection
