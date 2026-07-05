<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\ReportCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnonymousReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed categories
        ReportCategory::create(['name' => 'Sampah & Kebersihan', 'icon' => '🗑️', 'points' => 5]);
    }

    public function test_guest_can_access_anonymous_report_create_page(): void
    {
        $response = $this->get(route('laporan-anonim.create'));

        $response->assertStatus(200);
        $response->assertSee('Mode Anonim Aktif');
    }

    public function test_guest_can_submit_anonymous_report(): void
    {
        $category = ReportCategory::first();

        $response = $this->post(route('laporan-anonim.store'), [
            'title' => 'Sampah Menumpuk di Sungai',
            'category_id' => $category->id,
            'description' => 'Tumpukan sampah plastik menyumbat aliran sungai utama.',
            'address' => 'Jl. Sudirman No. 10',
            'latitude' => 0.5074,
            'longitude' => 101.4477,
            'anonymous_name' => 'Warga Peduli',
            'anonymous_contact' => '08123456789',
        ]);

        $response->assertRedirect(route('laporan-anonim.konfirmasi'));

        $this->assertDatabaseHas('reports', [
            'user_id' => null,
            'is_anonymous' => true,
            'anonymous_name' => 'Warga Peduli',
            'anonymous_contact' => '08123456789',
        ]);

        $report = Report::where('is_anonymous', true)->first();
        $this->assertNotNull($report->anonymous_code);
    }

    public function test_guest_can_track_anonymous_report_status(): void
    {
        $category = ReportCategory::first();
        $report = Report::create([
            'user_id' => null,
            'category_id' => $category->id,
            'title' => 'Sampah Menumpuk',
            'description' => 'Deskripsi tumpukan sampah.',
            'address' => 'Jl. Sudirman',
            'latitude' => 0.5074,
            'longitude' => 101.4477,
            'status' => 'pending',
            'is_anonymous' => true,
            'anonymous_code' => 'LA-999999',
        ]);

        // Cek via form
        $response = $this->post(route('laporan-anonim.cek'), [
            'code' => 'LA-999999',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Status Laporan Anonim');
        $response->assertSee('Sampah Menumpuk');
    }
}
