<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\TourDay;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        $tours = [
            [
                'slug' => '13-days-safaris',
                'name' => '13 Days Grand Tanzania Wildlife & Cultural Safari',
                'type' => 'combo',
                'days' => 13,
                'base_price_usd' => 4550.00,
                'summary' => 'Comprehensive journey across Tarangire, Lake Manyara, Serengeti, Ngorongoro Crater, Lake Eyasi Hadzabe tribe, and Arusha National Park.',
                'days_data' => [
                    ['day' => 1, 'title' => 'Arrival at Kilimanjaro International Airport (JRO)', 'dest' => 'Arusha', 'acc' => 'Arusha Serena Hotel', 'meals' => 'Dinner', 'act' => 'Airport pickup, safari briefing, rest and acclimatisation.'],
                    ['day' => 2, 'title' => 'Arusha to Tarangire National Park', 'dest' => 'Tarangire', 'acc' => 'Tarangire Safari Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Game drive among giant baobabs and large elephant herds.'],
                    ['day' => 3, 'title' => 'Full Day in Tarangire National Park', 'dest' => 'Tarangire', 'acc' => 'Tarangire Safari Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'River exploration, predators tracking, bird watching.'],
                    ['day' => 4, 'title' => 'Tarangire to Lake Manyara National Park', 'dest' => 'Lake Manyara', 'acc' => 'Manyara Secret Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Tree-climbing lions, flamingo flocks on alkaline soda lake, groundwater forest.'],
                    ['day' => 5, 'title' => 'Lake Manyara to Lake Eyasi (Cultural Tour)', 'dest' => 'Lake Eyasi', 'acc' => 'Kisima Ngeda Tented Camp', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Hadzabe hunter-gatherers archery, Datoga blacksmiths.'],
                    ['day' => 6, 'title' => 'Lake Eyasi to Central Serengeti (Seronera)', 'dest' => 'Central Serengeti', 'acc' => 'Kubukubu Tented Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Scenic drive through Ngorongoro Highlands, afternoon game drive in Seronera valley.'],
                    ['day' => 7, 'title' => 'Central Serengeti Full Day Exploration', 'dest' => 'Central Serengeti', 'acc' => 'Kubukubu Tented Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Tracking big cats (lions, leopards, cheetahs) along the Seronera River.'],
                    ['day' => 8, 'title' => 'Central Serengeti to Northern Serengeti', 'dest' => 'North Serengeti', 'acc' => 'Mara Under Canvas', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Game drive heading north along the migration corridor.'],
                    ['day' => 9, 'title' => 'Mara River Crossing & Northern Serengeti', 'dest' => 'North Serengeti', 'acc' => 'Mara Under Canvas', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Witnessing wildebeest river crossings and Nile crocodile encounters.'],
                    ['day' => 10, 'title' => 'Northern Serengeti to Ngorongoro Crater Rim', 'dest' => 'Ngorongoro Rim', 'acc' => 'Ngorongoro Serena Safari Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'En-route game drive with panoramic crater rim sunset views.'],
                    ['day' => 11, 'title' => 'Ngorongoro Crater Floor Tour', 'dest' => 'Ngorongoro Crater', 'acc' => 'Country Lodge Karatu', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => '6-hour crater descent, black rhino tracking, hippo pool lunch.'],
                    ['day' => 12, 'title' => 'Karatu to Arusha National Park', 'dest' => 'Arusha National Park', 'acc' => 'Mount Meru Game Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Walking safari with an armed ranger, colobus monkeys, Momella Lakes.'],
                    ['day' => 13, 'title' => 'Cultural Shopping & Departure Transfer', 'dest' => 'Arusha / JRO', 'acc' => 'Day Room available', 'meals' => 'Breakfast, Lunch', 'act' => 'Curio market visit, Maasai craft center, transfer to airport.'],
                ],
            ],
            [
                'slug' => '10-days-safaris',
                'name' => '10 Days Classic Northern Circuit Wildlife Safari',
                'type' => 'safari',
                'days' => 10,
                'base_price_usd' => 3450.00,
                'summary' => 'The ultimate safari experience combining Tarangire elephants, Lake Manyara, deep Serengeti exploration, and the Ngorongoro Caldera.',
                'days_data' => [
                    ['day' => 1, 'title' => 'Arrival in Arusha', 'dest' => 'Arusha', 'acc' => 'The African Tulip Hotel', 'meals' => 'Dinner', 'act' => 'Airport reception, transfer to hotel, safari orientation.'],
                    ['day' => 2, 'title' => 'Arusha to Tarangire National Park', 'dest' => 'Tarangire', 'acc' => 'Maramboi Tented Camp', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Game drive along Tarangire river, elephant sightings, birding.'],
                    ['day' => 3, 'title' => 'Tarangire to Lake Manyara National Park', 'dest' => 'Lake Manyara', 'acc' => 'Escarpment Luxury Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Groundwater forest canopy walk, baboon troops, flamingo viewing.'],
                    ['day' => 4, 'title' => 'Lake Manyara to Serengeti (Seronera)', 'dest' => 'Central Serengeti', 'acc' => 'Osinon Camp Serengeti', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Cross Ngorongoro conservation area, enter endless plains, kopjes game drive.'],
                    ['day' => 5, 'title' => 'Full Day Serengeti Big Cats Tracking', 'dest' => 'Central Serengeti', 'acc' => 'Osinon Camp Serengeti', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Sunrise game drive, Retima hippo pool, predator tracking.'],
                    ['day' => 6, 'title' => 'Serengeti Wildlife & Migration Corridors', 'dest' => 'Serengeti', 'acc' => 'Osinon Camp Serengeti', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Following the Great Migration herds across the central savannah.'],
                    ['day' => 7, 'title' => 'Serengeti to Ngorongoro Conservation Area', 'dest' => 'Ngorongoro Highlands', 'acc' => 'Rhino Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Visit Olduvai Gorge anthropological museum, crater rim view.'],
                    ['day' => 8, 'title' => 'Ngorongoro Crater Floor Safari', 'dest' => 'Ngorongoro Crater', 'acc' => 'Bougainvillea Safari Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Descent 600m to crater floor, Big Five tracking, lakeside picnic.'],
                    ['day' => 9, 'title' => 'Karatu Coffee Tour & Mto wa Mbu Cultural Walk', 'dest' => 'Karatu / Mto wa Mbu', 'acc' => 'Ilboru Safari Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Coffee farm walking tour, village banana plantation tour, local lunch.'],
                    ['day' => 10, 'title' => 'Arusha City Tour & Departure', 'dest' => 'Arusha / JRO', 'acc' => 'Departure', 'meals' => 'Breakfast, Lunch', 'act' => 'Tanzanite experience museum, souvenir craft market, airport drop-off.'],
                ],
            ],
            [
                'slug' => '9-days-safaris',
                'name' => '9 Days Migration Highlights & Crater Safari',
                'type' => 'safari',
                'days' => 9,
                'base_price_usd' => 3150.00,
                'summary' => 'Focused wildlife safari highlighting the Serengeti ecosystem, Ngorongoro Crater, and Tarangire National Park with premium tented camps.',
                'days_data' => [
                    ['day' => 1, 'title' => 'Welcome to Tanzania (Arusha)', 'dest' => 'Arusha', 'acc' => 'Kibo Palace Hotel', 'meals' => 'Dinner', 'act' => 'Meet and greet at airport, brief and overnight in Arusha.'],
                    ['day' => 2, 'title' => 'Arusha to Tarangire National Park', 'dest' => 'Tarangire', 'acc' => 'Sangaiwe Tented Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Game drive with picnic lunch, baobab trees and tree climbing pythons.'],
                    ['day' => 3, 'title' => 'Tarangire to Lake Manyara', 'dest' => 'Lake Manyara', 'acc' => 'Farm House Valley Karatu', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Afternoon game drive in Lake Manyara National Park.'],
                    ['day' => 4, 'title' => 'Karatu to Central Serengeti', 'dest' => 'Serengeti', 'acc' => 'Kati Kati Tented Camp', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Transit via Ngorongoro highlands into the great Serengeti plains.'],
                    ['day' => 5, 'title' => 'Serengeti Full Day Game Viewing', 'dest' => 'Serengeti', 'acc' => 'Kati Kati Tented Camp', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Full day tracking lions, cheetahs, leopards, zebras and wildebeest.'],
                    ['day' => 6, 'title' => 'Serengeti to Western or Northern Sector', 'dest' => 'Serengeti', 'acc' => 'Kati Kati Tented Camp', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Following migratory movements, sunset sundowner on the savannah.'],
                    ['day' => 7, 'title' => 'Serengeti to Ngorongoro Conservation Area', 'dest' => 'Ngorongoro', 'acc' => 'Ngorongoro Farm House', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Morning game drive, afternoon drive to Karatu highlands.'],
                    ['day' => 8, 'title' => 'Full Day Ngorongoro Crater Tour', 'dest' => 'Ngorongoro Crater', 'acc' => 'Ngorongoro Farm House', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Crater descent, high wildlife density observation, return to lodge.'],
                    ['day' => 9, 'title' => 'Return to Arusha & Flight Connection', 'dest' => 'Arusha', 'acc' => 'Departure', 'meals' => 'Breakfast, Lunch', 'act' => 'Scenic drive back to Arusha, day room and airport transfer.'],
                ],
            ],
            [
                'slug' => '6-days-umbwe-route',
                'name' => '6 Days Kilimanjaro Climb via Umbwe Route',
                'type' => 'climb',
                'days' => 6,
                'base_price_usd' => 2200.00,
                'summary' => 'The most direct and dramatic climbing route on Mount Kilimanjaro, suited for fit trekkers seeking steep ridges and spectacular mountain vistas.',
                'days_data' => [
                    ['day' => 1, 'title' => 'Umbwe Gate (1,600m) to Umbwe Cave Camp (2,850m)', 'dest' => 'Mount Kilimanjaro', 'acc' => 'Umbwe Cave Camp (Mountain Tents)', 'meals' => 'Lunch, Dinner', 'act' => 'Rainforest trekking along steep ridge between Lonzo and Umbwe rivers.'],
                    ['day' => 2, 'title' => 'Umbwe Cave Camp to Barranco Camp (3,900m)', 'dest' => 'Mount Kilimanjaro', 'acc' => 'Barranco Camp (Mountain Tents)', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Ascend through heath and moorland, giant senecios, view of Kibo peak.'],
                    ['day' => 3, 'title' => 'Barranco Camp to Karanga Camp (3,995m)', 'dest' => 'Mount Kilimanjaro', 'acc' => 'Karanga Camp (Mountain Tents)', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Scramble up the famous Barranco Breakfast Wall, traverse alpine valleys.'],
                    ['day' => 4, 'title' => 'Karanga Camp to Barafu Camp (4,673m)', 'dest' => 'Mount Kilimanjaro', 'acc' => 'Barafu Camp (Mountain Tents)', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Ascend to base camp on rocky alpine ridge, early dinner and rest for summit.'],
                    ['day' => 5, 'title' => 'Summit Night (Uhuru Peak 5,895m) & Descent to Mweka Camp (3,100m)', 'dest' => 'Uhuru Peak', 'acc' => 'Mweka Camp (Mountain Tents)', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Midnight summit push, sunrise at Stella Point, Uhuru summit celebration, long descent.'],
                    ['day' => 6, 'title' => 'Mweka Camp to Mweka Gate (1,640m) & Arusha Transfer', 'dest' => 'Moshi / Arusha', 'acc' => 'Hotel in Arusha', 'meals' => 'Breakfast, Lunch', 'act' => 'Descent through rainforest, receive summit certificates, celebration with mountain crew.'],
                ],
            ],
            [
                'slug' => '5-days-marangu-route',
                'name' => '5 Days Mount Kilimanjaro Climb (Marangu Route)',
                'type' => 'climb',
                'days' => 5,
                'base_price_usd' => 1950.00,
                'summary' => 'Known as the Coca-Cola Route, Marangu provides comfortable A-frame wooden hut accommodations throughout the ascent to Uhuru Peak.',
                'days_data' => [
                    ['day' => 1, 'title' => 'Marangu Gate (1,870m) to Mandara Hut (2,720m)', 'dest' => 'Mount Kilimanjaro', 'acc' => 'Mandara Huts', 'meals' => 'Lunch, Dinner', 'act' => 'Registration at Marangu Gate, steady walk through lush montane rainforest.'],
                    ['day' => 2, 'title' => 'Mandara Hut to Horombo Hut (3,720m)', 'dest' => 'Mount Kilimanjaro', 'acc' => 'Horombo Huts', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Trek past Maundi Crater into open moorland, expansive views of Mawenzi peak.'],
                    ['day' => 3, 'title' => 'Horombo Hut to Kibo Hut (4,700m)', 'dest' => 'Mount Kilimanjaro', 'acc' => 'Kibo Huts', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Cross the alpine desert Saddle between Mawenzi and Kibo, summit prep.'],
                    ['day' => 4, 'title' => 'Summit Attempt (Uhuru Peak 5,895m) & Descend to Horombo', 'dest' => 'Uhuru Peak', 'acc' => 'Horombo Huts', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Midnight start via Gilman Point to Uhuru Peak, celebrate summit, descend to Horombo.'],
                    ['day' => 5, 'title' => 'Horombo Hut to Marangu Gate & Return to Arusha', 'dest' => 'Marangu / Arusha', 'acc' => 'Hotel in Arusha', 'meals' => 'Breakfast, Lunch', 'act' => 'Final descent to park gate, certificate ceremony, return drive to hotel.'],
                ],
            ],
            [
                'slug' => 'bush-to-beach',
                'name' => '11 Days Bush to Beach (Serengeti Safari & Zanzibar Paradise)',
                'type' => 'combo',
                'days' => 11,
                'base_price_usd' => 3890.00,
                'summary' => 'The quintessential Tanzanian adventure: Tarangire, Ngorongoro Crater, and Serengeti wildlife safari followed by pristine turquoise waters and spice tours in Zanzibar.',
                'days_data' => [
                    ['day' => 1, 'title' => 'Arrival in Arusha', 'dest' => 'Arusha', 'acc' => 'Gran Melia Arusha', 'meals' => 'Dinner', 'act' => 'Arrive in Tanzania, transfer to luxury lodge, evening safari briefing.'],
                    ['day' => 2, 'title' => 'Arusha to Tarangire National Park', 'dest' => 'Tarangire', 'acc' => 'Tarangire Balloon Camp', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Game drive with packed lunch, elephant breeding herds and big cats.'],
                    ['day' => 3, 'title' => 'Tarangire to Ngorongoro Conservation Area', 'dest' => 'Ngorongoro Rim', 'acc' => 'Ngorongoro Lion\'s Paw Camp', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Morning game drive, scenic drive to the crater rim.'],
                    ['day' => 4, 'title' => 'Ngorongoro Crater Floor Safari', 'dest' => 'Ngorongoro Crater', 'acc' => 'Acacia Farm Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Full crater descent, black rhino tracking, hippo pool picnic.'],
                    ['day' => 5, 'title' => 'Ngorongoro to Serengeti National Park', 'dest' => 'Serengeti', 'acc' => 'Serengeti Serena Safari Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Game drive across the endless savannah, tracking cheetahs and lions.'],
                    ['day' => 6, 'title' => 'Full Day Serengeti Safari Adventure', 'dest' => 'Serengeti', 'acc' => 'Serengeti Serena Safari Lodge', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Sunrise hot air balloon option, wildlife tracking along Seronera valley.'],
                    ['day' => 7, 'title' => 'Fly Serengeti to Zanzibar Island', 'dest' => 'Stone Town, Zanzibar', 'acc' => 'Park Hyatt Zanzibar', 'meals' => 'Breakfast, Dinner', 'act' => 'Airstrip game drive, flight to Zanzibar, sunset dhow cruise in Stone Town.'],
                    ['day' => 8, 'title' => 'Stone Town Walking Tour & Spice Farm to Nungwi Beach', 'dest' => 'Zanzibar Beach', 'acc' => 'Zuri Zanzibar Resort', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Historic Stone Town UNESCO tour, fragrant spice plantation, transfer to beach.'],
                    ['day' => 9, 'title' => 'Zanzibar Beach Relaxation & Coral Reef Snorkelling', 'dest' => 'Nungwi / Kendwa', 'acc' => 'Zuri Zanzibar Resort', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Snorkelling trip to Mnemba Atoll, swimming in crystal blue waters.'],
                    ['day' => 10, 'title' => 'Leisure Day on Zanzibar Coast', 'dest' => 'Zanzibar Coast', 'acc' => 'Zuri Zanzibar Resort', 'meals' => 'Breakfast, Lunch, Dinner', 'act' => 'Water sports, coastal walk, fresh seafood dinner on the white sand.'],
                    ['day' => 11, 'title' => 'Departure from Zanzibar Airport (ZNZ)', 'dest' => 'Zanzibar Airport', 'acc' => 'Departure', 'meals' => 'Breakfast', 'act' => 'Final beach morning, transfer to Zanzibar International Airport for flight home.'],
                ],
            ],
        ];

        foreach ($tours as $t) {
            $daysData = $t['days_data'];
            unset($t['days_data']);

            $tour = Tour::updateOrCreate(['slug' => $t['slug']], $t);

            // Re-seed days
            $tour->tourDays()->delete();
            foreach ($daysData as $d) {
                TourDay::create([
                    'tour_id' => $tour->id,
                    'day_number' => $d['day'],
                    'title' => $d['title'],
                    'destination' => $d['dest'],
                    'accommodation_note' => $d['acc'],
                    'meals' => $d['meals'],
                    'activities' => $d['act'],
                    'description' => "Day {$d['day']}: {$d['title']}. Experience {$d['act']} Staying at {$d['acc']}.",
                ]);
            }
        }
    }
}
