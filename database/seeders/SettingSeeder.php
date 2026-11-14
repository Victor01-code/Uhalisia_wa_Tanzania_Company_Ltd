<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'company_name' => 'Uhalisia wa Tanzania Company Ltd',
            'company_tagline' => 'Safaris and Tours',
            'company_email' => 'info@uhalisiawatanzania.com',
            'company_phone' => '+255 784 590 722',
            'company_website' => 'www.uhalisiawatanzania.com',
            'company_offices' => 'Arusha & Dar es Salaam, Tanzania',
            'quote_validity_days' => '14',
            'default_deposit_percent' => '30',
            'email_signature' => "Warm regards from Tanzania,\n\nUhalisia wa Tanzania Company Ltd (Safaris and Tours)\nArusha & Dar es Salaam, Tanzania\nPhone: +255 784 590 722 | Email: info@uhalisiawatanzania.com\nWebsite: www.uhalisiawatanzania.com",
            'quote_terms' => "1. A deposit of 30% is required to secure park permits and lodge bookings.\n2. The balance is due 30 days prior to safari commencement.\n3. Quoted prices include 4x4 safari vehicle with pop-up roof, experienced English-speaking driver/guide, national park entrance fees, accommodation as specified, and meals on safari.\n4. Excludes international flights, Tanzanian entry visa, personal travel insurance, and driver/guide gratuities.",
        ];

        foreach ($settings as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }
    }
}
