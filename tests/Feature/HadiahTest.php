<?php

namespace Tests\Feature;

use App\Models\Reward;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HadiahTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed rewards
        $this->artisan('db:seed', ['--class' => 'RewardSeeder']);
    }

    public function test_guest_can_access_hadiah_catalogue(): void
    {
        $response = $this->get(route('hadiah'));

        $response->assertStatus(200);
        $response->assertSee('Toko Hadiah LaporHijau');
        $response->assertSee('Sertifikat Pelopor Hijau');
    }

    public function test_guest_cannot_redeem_reward(): void
    {
        $reward = Reward::first();

        $response = $this->post(route('hadiah.redeem', $reward));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_redeem_reward_with_sufficient_points(): void
    {
        $user = User::factory()->create(['points' => 200]);
        $reward = Reward::where('points_required', 100)->first();

        $response = $this->actingAs($user)->post(route('hadiah.redeem', $reward));

        $user->refresh();
        $this->assertEquals(100, $user->points);
        $this->assertDatabaseHas('reward_redemptions', [
            'user_id' => $user->id,
            'reward_id' => $reward->id,
        ]);

        $redemption = $user->rewardRedemptions()->first();
        $response->assertRedirect(route('hadiah.sertifikat', $redemption->certificate_code));
    }

    public function test_authenticated_user_cannot_redeem_reward_with_insufficient_points(): void
    {
        $user = User::factory()->create(['points' => 50]);
        $reward = Reward::where('points_required', 100)->first();

        $response = $this->actingAs($user)->post(route('hadiah.redeem', $reward));

        $response->assertSessionHas('error', 'Poin Anda tidak cukup untuk menukarkan hadiah ini.');
        $user->refresh();
        $this->assertEquals(50, $user->points);
    }
}
