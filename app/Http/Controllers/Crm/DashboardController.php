<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Quote;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'total_clients' => Client::count(),
            'total_opportunities' => Opportunity::count(),
            'open_opportunities' => Opportunity::whereDoesntHave('stage', function ($q) {
                $q->where('is_won', true)->orWhere('is_lost', true);
            })->count(),
            'won_opportunities' => Opportunity::whereHas('stage', function ($q) {
                $q->where('is_won', true);
            })->count(),
            'total_pipeline_value' => Opportunity::whereDoesntHave('stage', function ($q) {
                $q->where('is_won', true)->orWhere('is_lost', true);
            })->sum('amount'),
            'weighted_pipeline' => Opportunity::whereDoesntHave('stage', function ($q) {
                $q->where('is_won', true)->orWhere('is_lost', true);
            })->sum(DB::raw('amount * probability / 100')),
            'won_value' => Opportunity::whereHas('stage', function ($q) {
                $q->where('is_won', true);
            })->sum('amount'),
            'pending_quotes' => Quote::whereIn('status', ['draft', 'sent'])->count(),
            'total_quotes_value' => Quote::whereIn('status', ['draft', 'sent'])->sum('total'),
        ];

        $pipeline = Stage::ordered()->withCount(['opportunities' => function ($q) {
            $q->whereDoesntHave('stage', function ($sq) {
                $sq->where('is_won', true)->orWhere('is_lost', true);
            });
        }])->withSum(['opportunities' => function ($q) {
            $q->whereDoesntHave('stage', function ($sq) {
                $sq->where('is_won', true)->orWhere('is_lost', true);
            });
        }], 'amount')->get();

        $recentOpportunities = Opportunity::with(['client', 'stage'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingActivities = \App\Models\Activity::query()
            ->where('is_completed', false)
            ->where('due_date', '>=', now())
            ->orderBy('due_date')
            ->take(5)
            ->get();

        return view('crm.dashboard.index', compact(
            'stats',
            'pipeline',
            'recentOpportunities',
            'upcomingActivities'
        ));
    }
}
