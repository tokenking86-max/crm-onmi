@extends('crm.layouts.app')

@section('title', 'Editar Cotización')

@section('content')
<div class="max-w-4xl">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Editar Cotización {{ $quote->number }}</h1>

    <form method="POST" action="{{ route('crm.quotes.update', $quote) }}" class="space-y-6" x-data="quoteForm()">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente *</label>
                    <select name="client_id" x-model="clientId" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $quote->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Oportunidad *</label>
                    <select name="opportunity_id" x-model="opportunityId" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @foreach($opportunities as $opp)
                            <option value="{{ $opp->id }}" {{ old('opportunity_id', $quote->opportunity_id) == $opp->id ? 'selected' : '' }}>{{ $opp->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Válida hasta</label>
                    <input type="date" name="valid_until" value="{{ old('valid_until', $quote->valid_until?->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Ítems</h2>
                <button type="button" @click="addItem()" class="text-sm text-indigo-600 hover:underline">+ Agregar ítem</button>
            </div>
            <div class="space-y-3">
                <template x-for="(item, index) in items" :key="index">
                    <div class="grid grid-cols-12 gap-3 items-end">
                        <div class="col-span-5">
                            <input type="text" :name="'items[' + index + '][description]'" x-model="item.description" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div class="col-span-2">
                            <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity" min="1" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" @input="calculateTotal(index)">
                        </div>
                        <div class="col-span-2">
                            <input type="number" :name="'items[' + index + '][unit_price]'" x-model="item.unit_price" step="0.01" min="0" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" @input="calculateTotal(index)">
                        </div>
                        <div class="col-span-1">
                            <input type="number" :name="'items[' + index + '][discount]'" x-model="item.discount" min="0" max="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" @input="calculateTotal(index)">
                        </div>
                        <div class="col-span-1 text-right text-sm font-medium" x-text="'S/ ' + item.total.toFixed(2)"></div>
                        <div class="col-span-1">
                            <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 text-sm">X</button>
                        </div>
                    </div>
                </template>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-end">
                <div class="w-64 space-y-1 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Subtotal:</span><span x-text="'S/ ' + subtotal.toFixed(2)"></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">IGV (18%):</span><span x-text="'S/ ' + tax.toFixed(2)"></span></div>
                    <div class="flex justify-between font-bold text-lg"><span>Total:</span><span class="text-indigo-600" x-text="'S/ ' + total.toFixed(2)"></span></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('notes', $quote->notes) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Términos y Condiciones</label>
                <textarea name="terms" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('terms', $quote->terms) }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('crm.quotes.show', $quote) }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">Actualizar</button>
        </div>
    </form>
</div>

<script>
function quoteForm() {
    return {
        clientId: '{{ old('client_id', $quote->client_id) }}',
        opportunityId: '{{ old('opportunity_id', $quote->opportunity_id) }}',
        items: @json(old('items', $quote->items->map(fn($i) => ['description' => $i->description, 'quantity' => $i->quantity, 'unit_price' => $i->unit_price, 'discount' => $i->discount, 'total' => $i->total]))->toArray()),
        subtotal: 0, tax: 0, total: 0,
        addItem() { this.items.push({ description: '', quantity: 1, unit_price: 0, discount: 0, total: 0 }); },
        removeItem(i) { if (this.items.length > 1) { this.items.splice(i, 1); this.recalculate(); } },
        calculateTotal(i) { const item = this.items[i]; const line = item.quantity * item.unit_price; item.total = line - (line * (item.discount || 0) / 100); this.recalculate(); },
        recalculate() { this.subtotal = this.items.reduce((s, item) => s + (item.total || 0), 0); this.tax = this.subtotal * 0.18; this.total = this.subtotal + this.tax; },
        init() { this.items.forEach((_, i) => this.calculateTotal(i)); }
    }
}
</script>
@endsection
