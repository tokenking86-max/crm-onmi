<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Database\Seeder;

class CrmSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@ommisolutions.com'],
            [
                'name' => 'Admin Ommi',
                'password' => bcrypt('password'),
            ]
        );

        $leads = [
            ['name' => 'TechCorp Perú', 'company' => 'TechCorp SAC', 'email' => 'contacto@techcorp.pe', 'phone' => '+51 999 111 222', 'source' => 'website', 'status' => 'new', 'estimated_value' => 25000],
            ['name' => 'Inversiones Lima', 'company' => 'Inversiones Lima SA', 'email' => 'info@invlima.pe', 'phone' => '+51 999 333 444', 'source' => 'referral', 'status' => 'contacted', 'estimated_value' => 45000],
            ['name' => 'Retail Max', 'company' => 'Retail Max EIRL', 'email' => 'ventas@retailmax.pe', 'phone' => '+51 999 555 666', 'source' => 'social_media', 'status' => 'qualified', 'estimated_value' => 18000],
            ['name' => 'Constructora Norte', 'company' => 'Constructora Norte SAC', 'email' => 'proyectos@constnorte.pe', 'phone' => '+51 999 777 888', 'source' => 'event', 'status' => 'new', 'estimated_value' => 62000],
            ['name' => 'Agro Export', 'company' => 'Agro Export SRL', 'email' => 'contacto@agroexport.pe', 'phone' => '+51 999 000 111', 'source' => 'cold_call', 'status' => 'contacted', 'estimated_value' => 12000],
        ];

        foreach ($leads as $leadData) {
            Lead::create(array_merge($leadData, ['assigned_to' => $user->id]));
        }

        $clientsData = [
            ['name' => 'Digital Solutions', 'company' => 'Digital Solutions SAC', 'email' => 'admin@digitalsol.pe', 'phone' => '+51 988 111 222', 'city' => 'Lima', 'country' => 'Perú'],
            ['name' => 'MedTech Peru', 'company' => 'MedTech Peru SRL', 'email' => 'info@medtech.pe', 'phone' => '+51 988 333 444', 'city' => 'Arequipa', 'country' => 'Perú'],
            ['name' => 'Finance Group', 'company' => 'Finance Group SA', 'email' => 'contacto@fingroup.pe', 'phone' => '+51 988 555 666', 'city' => 'Lima', 'country' => 'Perú'],
        ];

        $clients = [];
        foreach ($clientsData as $clientData) {
            $clients[] = Client::create(array_merge($clientData, ['assigned_to' => $user->id]));
        }

        $stages = Stage::orderBy('order')->get();

        $opportunitiesData = [
            ['title' => 'Sistema de Gestión Digital', 'client' => 0, 'amount' => 35000, 'probability' => 80, 'stage_offset' => 3],
            ['title' => 'App Móvil MedTech', 'client' => 1, 'amount' => 28000, 'probability' => 60, 'stage_offset' => 2],
            ['title' => 'Portal Financiero', 'client' => 2, 'amount' => 52000, 'probability' => 40, 'stage_offset' => 1],
            ['title' => 'Consultoría Cloud', 'client' => 0, 'amount' => 15000, 'probability' => 90, 'stage_offset' => 4],
        ];

        $opportunities = [];
        foreach ($opportunitiesData as $oppData) {
            $stageIndex = min($oppData['stage_offset'], $stages->count() - 1);
            $opportunities[] = Opportunity::create([
                'title' => $oppData['title'],
                'client_id' => $clients[$oppData['client']]->id,
                'stage_id' => $stages[$stageIndex]->id,
                'amount' => $oppData['amount'],
                'probability' => $oppData['probability'],
                'expected_close_date' => now()->addDays(rand(15, 60)),
                'assigned_to' => $user->id,
            ]);
        }

        // Quote for first opportunity
        if (!empty($opportunities[0])) {
            $quote = Quote::create([
                'number' => Quote::generateNumber(),
                'opportunity_id' => $opportunities[0]->id,
                'client_id' => $clients[0]->id,
                'status' => 'sent',
                'valid_until' => now()->addDays(30),
                'created_by' => $user->id,
                'notes' => 'Incluye soporte técnico por 6 meses.',
                'terms' => 'Pago: 50% adelanto, 50% contra entrega.',
            ]);

            $items = [
                ['description' => 'Licencia de software anual', 'quantity' => 1, 'unit_price' => 12000, 'discount' => 0],
                ['description' => 'Implementación y configuración', 'quantity' => 40, 'unit_price' => 150, 'discount' => 10],
                ['description' => 'Capacitación al personal', 'quantity' => 8, 'unit_price' => 200, 'discount' => 0],
            ];

            foreach ($items as $itemData) {
                $itemData['total'] = $itemData['quantity'] * $itemData['unit_price'] * (1 - $itemData['discount'] / 100);
                $quote->items()->create($itemData);
            }

            $quote->recalculate();
        }
    }
}
