<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'type' => 'required|in:call,email,meeting,task,note,whatsapp',
            'activityable_type' => 'required|in:lead,client,opportunity',
            'activityable_id' => 'required|integer',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        Activity::create($validated);

        return back()->with('success', 'Actividad registrada.');
    }

    public function complete(Activity $activity)
    {
        $activity->markCompleted();

        return back()->with('success', 'Actividad completada.');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return back()->with('success', 'Actividad eliminada.');
    }
}
