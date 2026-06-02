<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Opportunity;
use App\Models\Stage;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    public function index(Request $request)
    {
        $query = Opportunity::with(['client', 'stage', 'assignee']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('stage_id')) {
            $query->where('stage_id', $request->stage_id);
        }

        $opportunities = $query->latest()->paginate(15);
        $stages = Stage::ordered()->get();

        return view('crm.opportunities.index', compact('opportunities', 'stages'));
    }

    public function pipeline()
    {
        $stages = Stage::ordered()->with(['opportunities' => function ($q) {
            $q->with(['client', 'assignee']);
        }])->get();

        return view('crm.opportunities.pipeline', compact('stages'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $stages = Stage::ordered()->get();

        return view('crm.opportunities.create', compact('clients', 'stages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'stage_id' => 'required|exists:stages,id',
            'amount' => 'required|numeric|min:0',
            'probability' => 'required|numeric|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $validated['assigned_to'] = auth()->id();

        Opportunity::create($validated);

        return redirect()->route('crm.opportunities.index')
            ->with('success', 'Oportunidad creada correctamente.');
    }

    public function show(Opportunity $opportunity)
    {
        $opportunity->load(['client', 'stage', 'assignee', 'quotes.items', 'activities' => function ($q) {
            $q->latest()->take(10);
        }]);

        return view('crm.opportunities.show', compact('opportunity'));
    }

    public function edit(Opportunity $opportunity)
    {
        $clients = Client::orderBy('name')->get();
        $stages = Stage::ordered()->get();

        return view('crm.opportunities.edit', compact('opportunity', 'clients', 'stages'));
    }

    public function update(Request $request, Opportunity $opportunity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'stage_id' => 'required|exists:stages,id',
            'amount' => 'required|numeric|min:0',
            'probability' => 'required|numeric|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'actual_close_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $stage = Stage::find($validated['stage_id']);
        if ($stage->is_won || $stage->is_lost) {
            $validated['actual_close_date'] = $validated['actual_close_date'] ?? now();
        }

        $opportunity->update($validated);

        return redirect()->route('crm.opportunities.show', $opportunity)
            ->with('success', 'Oportunidad actualizada correctamente.');
    }

    public function destroy(Opportunity $opportunity)
    {
        $opportunity->delete();

        return redirect()->route('crm.opportunities.index')
            ->with('success', 'Oportunidad eliminada correctamente.');
    }

    public function updateStage(Request $request, Opportunity $opportunity)
    {
        $request->validate([
            'stage_id' => 'required|exists:stages,id',
        ]);

        $stage = Stage::find($request->stage_id);
        $opportunity->update([
            'stage_id' => $stage->id,
            'actual_close_date' => ($stage->is_won || $stage->is_lost) ? now() : null,
        ]);

        return back()->with('success', 'Etapa actualizada.');
    }
}
