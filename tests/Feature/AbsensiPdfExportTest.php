<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class AbsensiPdfExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/sekretaris/absensi/export-pdf');

        $response->assertRedirect(route('login'));
    }

    public function test_anggota_role_cannot_access_pdf_export(): void
    {
        $user = User::create([
            'username' => 'anggota_test',
            'password' => bcrypt('password'),
            'role' => 'Anggota',
        ]);

        $response = $this->actingAs($user)->get('/sekretaris/absensi/export-pdf');

        $response->assertStatus(403);
    }

    public function test_sekretaris_role_can_export_pdf(): void
    {
        $user = User::create([
            'username' => 'sekre_test',
            'password' => bcrypt('password'),
            'role' => 'Sekretaris',
        ]);

        $response = $this->actingAs($user)->get('/sekretaris/absensi/export-pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
