<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Opportunity;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Quote::with(['client', 'opportunity', 'creator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $quotes = $query->latest()->paginate(15);

        return view('crm.quotes.index', compact('quotes'));
    }

    public function create(Request $request)
    {
        $clients = Client::orderBy('name')->get();
        $opportunities = Opportunity::orderBy('title')->get();

        $selectedClient = $request->client_id;
        $selectedOpportunity = $request->opportunity_id;

        return view('crm.quotes.create', compact('clients', 'opportunities', 'selectedClient', 'selectedOpportunity'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'opportunity_id' => 'required|exists:opportunities,id',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0|max:100',
        ]);

        $validated['number'] = Quote::generateNumber();
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'draft';

        $itemsData = $validated['items'];
        unset($validated['items']);

        $quote = Quote::create($validated);

        foreach ($itemsData as $itemData) {
            $itemData['discount'] = $itemData['discount'] ?? 0;
            $itemData['total'] = $itemData['quantity'] * $itemData['unit_price'] * (1 - $itemData['discount'] / 100);
            $quote->items()->create($itemData);
        }

        $quote->recalculate();

        return redirect()->route('crm.quotes.show', $quote)
            ->with('success', 'Cotización creada correctamente.');
    }

    public function show(Quote $quote)
    {
        $quote->load(['client', 'opportunity', 'creator', 'items']);

        return view('crm.quotes.show', compact('quote'));
    }

    public function edit(Quote $quote)
    {
        $clients = Client::orderBy('name')->get();
        $opportunities = Opportunity::orderBy('title')->get();

        return view('crm.quotes.edit', compact('quote', 'clients', 'opportunities'));
    }

    public function update(Request $request, Quote $quote)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'opportunity_id' => 'required|exists:opportunities,id',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0|max:100',
        ]);

        $itemsData = $validated['items'];
        unset($validated['items']);

        $quote->update($validated);

        $quote->items()->delete();
        foreach ($itemsData as $itemData) {
            $itemData['discount'] = $itemData['discount'] ?? 0;
            $itemData['total'] = $itemData['quantity'] * $itemData['unit_price'] * (1 - $itemData['discount'] / 100);
            $quote->items()->create($itemData);
        }

        $quote->recalculate();

        return redirect()->route('crm.quotes.show', $quote)
            ->with('success', 'Cotización actualizada correctamente.');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('crm.quotes.index')
            ->with('success', 'Cotización eliminada correctamente.');
    }

    public function send(Quote $quote)
    {
        $quote->update(['status' => 'sent']);

        return back()->with('success', 'Cotización enviada al cliente.');
    }

    public function approve(Quote $quote)
    {
        $quote->update(['status' => 'approved']);

        return back()->with('success', 'Cotización aprobada.');
    }

    public function reject(Quote $quote)
    {
        $quote->update(['status' => 'rejected']);

        return back()->with('success', 'Cotización rechazada.');
    }
}
