<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\TourDay;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourDayTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Tour $tour;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Manager',
            'email' => 'admin@uhalisiawatanzania.com',
            'password' => bcrypt('AdminPass123!'),
            'role' => 'admin',
            'active' => true,
        ]);

        $this->tour = Tour::create([
            'name' => '4-Day Northern Circuit Special',
            'slug' => '4-day-northern-circuit',
            'type' => 'safari',
            'days' => 4,
            'base_price_usd' => 1800.00,
            'active' => true,
        ]);
    }

    public function test_can_add_blueprint_day_to_tour_template(): void
    {
        $response = $this->actingAs($this->admin)
                         ->post("/admin/tours/{$this->tour->id}/days", [
                             'day_number' => 1,
                             'title' => 'Arusha to Tarangire National Park',
                             'destination' => 'Tarangire',
                             'accommodation_note' => 'Tarangire Safari Lodge',
                             'meals' => 'Lunch, Dinner',
                             'activities' => 'Game drive among ancient baobabs and elephant herds',
                             'description' => 'Morning pickup from Arusha and transfer into Tarangire.',
                         ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('tour_days', [
            'tour_id' => $this->tour->id,
            'day_number' => 1,
            'title' => 'Arusha to Tarangire National Park',
            'destination' => 'Tarangire',
        ]);
    }

    public function test_can_update_blueprint_day(): void
    {
        $day = TourDay::create([
            'tour_id' => $this->tour->id,
            'day_number' => 2,
            'title' => 'Tarangire to Serengeti',
            'destination' => 'Serengeti',
            'accommodation_note' => 'Serengeti Tented Camp',
            'meals' => 'Breakfast, Lunch, Dinner',
        ]);

        $response = $this->actingAs($this->admin)
                         ->put("/admin/tours/{$this->tour->id}/days/{$day->id}", [
                             'day_number' => 2,
                             'title' => 'Tarangire to Central Serengeti (Seronera)',
                             'destination' => 'Central Serengeti',
                             'accommodation_note' => 'Seronera Wildlife Lodge',
                             'meals' => 'All Meals',
                             'activities' => 'Transit game drive via Ngorongoro conservation area',
                         ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('tour_days', [
            'id' => $day->id,
            'title' => 'Tarangire to Central Serengeti (Seronera)',
            'destination' => 'Central Serengeti',
        ]);
    }

    public function test_can_delete_blueprint_day_and_remaining_days_are_resequenced(): void
    {
        $day1 = TourDay::create(['tour_id' => $this->tour->id, 'day_number' => 1, 'title' => 'Day 1']);
        $day2 = TourDay::create(['tour_id' => $this->tour->id, 'day_number' => 2, 'title' => 'Day 2']);
        $day3 = TourDay::create(['tour_id' => $this->tour->id, 'day_number' => 3, 'title' => 'Day 3']);

        $response = $this->actingAs($this->admin)
                         ->delete("/admin/tours/{$this->tour->id}/days/{$day2->id}");

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('tour_days', ['id' => $day2->id]);

        // Day 3 should have been renumbered to Day 2
        $this->assertEquals(2, $day3->fresh()->day_number);
    }
}
