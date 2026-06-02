@extends('crm.layouts.app')

@section('title', 'Cotización: ' . $quote->number)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Cotización {{ $quote->number }}</h1>
            <p class="text-gray-500">{{ $quote->client->name }} | {{ $quote->opportunity->title }}</p>
        </div>
        <div class="flex space-x-3">
            @if($quote->status === 'draft')
                <form method="POST" action="{{ route('crm.quotes.send', $quote) }}">
                    @csrf
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">Enviar al Cliente</button>
                </form>
            @endif
            @if($quote->status === 'sent')
                <form method="POST" action="{{ route('crm.quotes.approve', $quote) }}">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium">Aprobar</button>
                </form>
                <form method="POST" action="{{ route('crm.quotes.reject', $quote) }}">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition font-medium">Rechazar</button>
                </form>
            @endif
            <a href="{{ route('crm.quotes.edit', $quote) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition font-medium">Editar</a>
        </div>
    </div>

    <!-- Quote Document -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h2 class="text-3xl font-bold text-indigo-600">COTIZACIÓN</h2>
                <p class="font-mono text-lg text-gray-600 mt-1">{{ $quote->number }}</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-lg text-gray-900">Ommi Solutions</p>
                <p class="text-sm text-gray-500">Arquitectura de Soluciones</p>
                <p class="text-sm text-gray-500">Desarrollo de Software</p>
            </div>
        </div>

        <!-- Client & Dates -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Cliente</h3>
                <p class="font-semibold text-gray-900">{{ $quote->client->name }}</p>
                @if($quote->client->company)<p class="text-sm text-gray-600">{{ $quote->client->company }}</p>@endif
                @if($quote->client->email)<p class="text-sm text-gray-600">{{ $quote->client->email }}</p>@endif
                @if($quote->client->phone)<p class="text-sm text-gray-600">{{ $quote->client->phone }}</p>@endif
            </div>
            <div class="text-right">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Fechas</h3>
                <p class="text-sm text-gray-600">Fecha: {{ $quote->created_at->format('d/m/Y') }}</p>
                @if($quote->valid_until)
                    <p class="text-sm text-gray-600">Válida hasta: {{ $quote->valid_until->format('d/m/Y') }}</p>
                @endif
                <p class="mt-2">
                    @switch($quote->status)
                        @case('draft') <span class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">Borrador</span> @break
                        @case('sent') <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-600">Enviada</span> @break
                        @case('approved') <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-600">Aprobada</span> @break
                        @case('rejected') <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-600">Rechazada</span> @break
                    @endswitch
                </p>
            </div>
        </div>

        <!-- Items Table -->
        <table class="w-full mb-8">
            <thead>
                <tr class="border-b-2 border-gray-200">
                    <th class="text-left py-3 text-sm font-semibold text-gray-600">Descripción</th>
                    <th class="text-right py-3 text-sm font-semibold text-gray-600">Cant.</th>
                    <th class="text-right py-3 text-sm font-semibold text-gray-600">P. Unitario</th>
                    <th class="text-right py-3 text-sm font-semibold text-gray-600">Dto.</th>
                    <th class="text-right py-3 text-sm font-semibold text-gray-600">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->items as $item)
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-sm">{{ $item->description }}</td>
                        <td class="py-3 text-sm text-right">{{ $item->quantity }}</td>
                        <td class="py-3 text-sm text-right">S/ {{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-3 text-sm text-right">{{ $item->discount }}%</td>
                        <td class="py-3 text-sm text-right font-medium">S/ {{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="flex justify-end">
            <div class="w-64 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal:</span>
                    <span>S/ {{ number_format($quote->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">IGV ({{ $quote->tax_rate }}%):</span>
                    <span>S/ {{ number_format($quote->tax_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t-2 border-gray-200 pt-2">
                    <span>Total:</span>
                    <span class="text-indigo-600">S/ {{ number_format($quote->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Notes & Terms -->
        @if($quote->notes || $quote->terms)
            <div class="grid grid-cols-2 gap-8 mt-8 pt-8 border-t border-gray-200">
                @if($quote->notes)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Notas</h3>
                        <p class="text-sm text-gray-600 whitespace-pre-line">{{ $quote->notes }}</p>
                    </div>
                @endif
                @if($quote->terms)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Términos y Condiciones</h3>
                        <p class="text-sm text-gray-600 whitespace-pre-line">{{ $quote->terms }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
