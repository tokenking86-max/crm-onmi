@extends('crm.layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Editar Cliente</h1>

    <form method="POST" action="{{ route('crm.clients.update', $client) }}" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 space-y-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                <input type="text" name="name" value="{{ old('name', $client->name) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
                <input type="text" name="company" value="{{ old('company', $client->company) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $client->email) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone', $client->phone) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <input type="text" name="address" value="{{ old('address', $client->address) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad</label>
                <input type="text" name="city" value="{{ old('city', $client->city) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">País</label>
                <input type="text" name="country" value="{{ old('country', $client->country) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
            <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('notes', $client->notes) }}</textarea>
        </div>
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('crm.clients.show', $client) }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">Actualizar</button>
        </div>
    </form>
</div>
@endsection
