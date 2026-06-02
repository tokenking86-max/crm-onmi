<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Client;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with('assignee');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $leads = $query->latest()->paginate(15);

        return view('crm.leads.index', compact('leads'));
    }

    public function create()
    {
        return view('crm.leads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'estimated_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['assigned_to'] = auth()->id();
        $validated['status'] = 'new';

        Lead::create($validated);

        return redirect()->route('crm.leads.index')
            ->with('success', 'Lead creado correctamente.');
    }

    public function show(Lead $lead)
    {
        $lead->load(['assignee', 'activities' => function ($q) {
            $q->latest()->take(10);
        }]);

        return view('crm.leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        return view('crm.leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'status' => 'required|in:new,contacted,qualified,unqualified',
            'estimated_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $lead->update($validated);

        return redirect()->route('crm.leads.show', $lead)
            ->with('success', 'Lead actualizado correctamente.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('crm.leads.index')
            ->with('success', 'Lead eliminado correctamente.');
    }

    public function convert(Lead $lead)
    {
        $client = $lead->convertToClient();

        return redirect()->route('crm.clients.show', $client)
            ->with('success', 'Lead convertido a cliente exitosamente.');
    }
}
