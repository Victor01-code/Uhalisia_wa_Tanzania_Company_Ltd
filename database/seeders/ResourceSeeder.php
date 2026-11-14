<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Guide;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            ['name' => 'Serengeti Cruiser 01', 'plate_number' => 'T 142 DAA', 'type' => 'Toyota Land Cruiser 4x4 (Extended)', 'seats' => 7, 'active' => true, 'notes' => 'Equipped with pop-up roof, inverter, fridge, and HF radio.'],
            ['name' => 'Ngorongoro Cruiser 02', 'plate_number' => 'T 285 EBB', 'type' => 'Toyota Land Cruiser 4x4 (Extended)', 'seats' => 7, 'active' => true, 'notes' => 'Dual spare tires, high ground clearance, USB charging ports at every seat.'],
            ['name' => 'Kilimanjaro Cruiser 03', 'plate_number' => 'T 519 DCC', 'type' => 'Toyota Land Cruiser 4x4 (Standard)', 'seats' => 5, 'active' => true, 'notes' => 'Perfect for private couples or small family groups.'],
            ['name' => 'Tarangire Safari Van 04', 'plate_number' => 'T 390 EDD', 'type' => 'Toyota HiAce 4x4', 'seats' => 8, 'active' => true, 'notes' => 'Used for airport transfers and cultural day tours around Arusha.'],
        ];

        foreach ($vehicles as $v) {
            Vehicle::updateOrCreate(['plate_number' => $v['plate_number']], $v);
        }

        $drivers = [
            ['name' => 'Emmanuel Mushi', 'phone' => '+255 754 112 233', 'languages' => 'English, Swahili', 'licence_number' => 'DL-TZ-88410', 'active' => true],
            ['name' => 'Juma Rashid', 'phone' => '+255 765 223 344', 'languages' => 'English, Swahili, French', 'licence_number' => 'DL-TZ-77192', 'active' => true],
            ['name' => 'David Mollel', 'phone' => '+255 784 334 455', 'languages' => 'English, Swahili, German', 'licence_number' => 'DL-TZ-66281', 'active' => true],
            ['name' => 'Salim Bakari', 'phone' => '+255 713 445 566', 'languages' => 'English, Swahili', 'licence_number' => 'DL-TZ-55374', 'active' => true],
        ];

        foreach ($drivers as $d) {
            Driver::updateOrCreate(['licence_number' => $d['licence_number']], $d);
        }

        $guides = [
            ['name' => 'Peter Kimaro', 'phone' => '+255 782 556 677', 'languages' => 'English, Swahili, Spanish', 'permit_number' => 'KILI-GUIDE-3310', 'active' => true],
            ['name' => 'Baraka Mrema', 'phone' => '+255 759 667 788', 'languages' => 'English, Swahili, Italian', 'permit_number' => 'TANAPA-NAT-1402', 'active' => true],
            ['name' => 'Goodluck Shirima', 'phone' => '+255 768 778 899', 'languages' => 'English, Swahili', 'permit_number' => 'TANAPA-NAT-1598', 'active' => true],
        ];

        foreach ($guides as $g) {
            Guide::updateOrCreate(['permit_number' => $g['permit_number']], $g);
        }
    }
}
