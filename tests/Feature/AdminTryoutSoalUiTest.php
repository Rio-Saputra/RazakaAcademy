<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Package;
use App\Models\Tryout;
use App\Models\Question;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminTryoutSoalUiTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;
    protected $tryout;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin Razaka Test',
            'email' => 'admin_test@razaka.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create sample package
        $package = Package::create([
            'name' => 'Paket Tryout Premium Test',
            'price' => 100000,
        ]);

        // Create sample tryout
        $this->tryout = Tryout::create([
            'title' => 'Tryout Akbar SKD CPNS 2026',
            'package_id' => $package->id,
            'duration' => 100,
            'batas_pengerjaan' => 3,
        ]);

        // Create sample questions across different categories
        Question::create([
            'tryout_id' => $this->tryout->id,
            'question_text' => 'Soal TWK Pancasila',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'option_e' => 'E',
            'correct_answer' => 'A',
            'jenis_soal' => 'TWK',
        ]);

        Question::create([
            'tryout_id' => $this->tryout->id,
            'question_text' => 'Soal TIU Deret Angka',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'option_e' => 'E',
            'correct_answer' => 'B',
            'jenis_soal' => 'TIU',
        ]);

        Question::create([
            'tryout_id' => $this->tryout->id,
            'question_text' => 'Soal TKP Pelayanan Publik',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'option_e' => 'E',
            'correct_answer' => 'A',
            'jenis_soal' => 'TKP',
            'option_points' => ['A' => 5, 'B' => 4, 'C' => 3, 'D' => 2, 'E' => 1],
        ]);
    }

    public function test_admin_can_view_tryout_index_page_with_stats()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.tryout.index'));

        $response->assertStatus(200);

        // Verify page layout and stats cards are visible
        $response->assertSee('Kelola Ujian Tryout');
        $response->assertSee('Total Tryout Aktif');
        $response->assertSee('Total Soal Terdistribusi');
        $response->assertSee('Rata-rata Durasi Ujian');

        // Verify the tryout created is displayed in table
        $response->assertSee('Tryout Akbar SKD CPNS 2026');
        $response->assertSee('100 Menit');
    }

    public function test_admin_can_view_soal_index_page_with_dynamic_stats_when_tryout_selected()
    {
        // View with tryout selected
        $response = $this->actingAs($this->admin)
            ->get(route('admin.soal.index', ['tryout_id' => $this->tryout->id]));

        $response->assertStatus(200);

        // Verify statistics grid header & breakdown values are correct
        $response->assertSee('Total Soal (Target: 110)');
        $response->assertSee('Soal TWK (Target: 30)');
        $response->assertSee('Soal TIU (Target: 35)');
        $response->assertSee('Soal TKP (Target: 45)');

        // We added 3 questions: 1 TWK, 1 TIU, 1 TKP.
        // Let's assert we see these numbers on the page in format "count / target"
        $response->assertSee('3 / 110'); // Total Soal count
        $response->assertSee('1 / 30');  // TWK Soal count
        $response->assertSee('1 / 35');  // TIU Soal count
        $response->assertSee('1 / 45');  // TKP Soal count

        // Verify question texts and details are present
        $response->assertSee('Soal TWK Pancasila');
        $response->assertSee('Soal TIU Deret Angka');
        $response->assertSee('Soal TKP Pelayanan Publik');
    }

    public function test_admin_sees_placeholder_when_no_tryout_selected_on_soal_index()
    {
        // View without tryout selected
        $response = $this->actingAs($this->admin)
            ->get(route('admin.soal.index'));

        $response->assertStatus(200);

        // Verify select prompt is shown
        $response->assertSee('Pilih Tryout Terlebih Dahulu');
        $response->assertSee('Silakan pilih salah satu paket tryout dari dropdown filter di atas');

        // Verify stats breakdown is NOT rendered
        $response->assertDontSee('Total Soal (Target: 110)');
    }
}
