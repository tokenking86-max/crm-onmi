<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with('assignee');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(15);

        return view('crm.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('crm.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['assigned_to'] = auth()->id();

        Client::create($validated);

        return redirect()->route('crm.clients.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function show(Client $client)
    {
        $client->load([
            'assignee',
            'opportunities.stage',
            'quotes',
            'activities' => function ($q) {
                $q->latest()->take(10);
            },
        ]);

        $totalOpportunities = $client->opportunities->sum('amount');
        $wonValue = $client->opportunities->filter(fn($o) => $o->stage->is_won)->sum('amount');

        return view('crm.clients.show', compact('client', 'totalOpportunities', 'wonValue'));
    }

    public function edit(Client $client)
    {
        return view('crm.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()->route('crm.clients.show', $client)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('crm.clients.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
