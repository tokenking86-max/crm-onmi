<?php

use App\Http\Controllers\Crm\ActivityController;
use App\Http\Controllers\Crm\ClientController;
use App\Http\Controllers\Crm\DashboardController;
use App\Http\Controllers\Crm\LeadController;
use App\Http\Controllers\Crm\OpportunityController;
use App\Http\Controllers\Crm\QuoteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('crm')->name('crm.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Leads
    Route::resource('leads', LeadController::class);
    Route::post('leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');

    // Clients
    Route::resource('clients', ClientController::class);

    // Opportunities
    Route::get('pipeline', [OpportunityController::class, 'pipeline'])->name('opportunities.pipeline');
    Route::post('opportunities/{opportunity}/stage', [OpportunityController::class, 'updateStage'])->name('opportunities.update-stage');
    Route::resource('opportunities', OpportunityController::class);

    // Quotes
    Route::post('quotes/{quote}/send', [QuoteController::class, 'send'])->name('quotes.send');
    Route::post('quotes/{quote}/approve', [QuoteController::class, 'approve'])->name('quotes.approve');
    Route::post('quotes/{quote}/reject', [QuoteController::class, 'reject'])->name('quotes.reject');
    Route::resource('quotes', QuoteController::class);

    // Activities
    Route::post('activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::post('activities/{activity}/complete', [ActivityController::class, 'complete'])->name('activities.complete');
    Route::delete('activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');
});
