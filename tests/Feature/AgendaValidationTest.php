<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\AgendaRapat;
use Tests\TestCase;

class AgendaValidationTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::create([
            'username' => 'sekre_test',
            'password' => bcrypt('password'),
            'role' => 'Sekretaris',
        ]);
    }

    public function test_cannot_create_agenda_in_the_past(): void
    {
        $response = $this->actingAs($this->user)->post('/sekretaris/agenda', [
            'judul_agenda' => 'Rapat Masa Lalu',
            'deskripsi' => 'Rapat ini di masa lalu',
            'tanggal_rapat' => date('Y-m-d', strtotime('-1 day')),
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '10:00',
            'lokasi' => 'Zoom',
        ]);

        $response->assertSessionHasErrors('tanggal_rapat');
    }

    public function test_cannot_create_duplicate_agenda_on_same_day(): void
    {
        // First agenda
        AgendaRapat::create([
            'judul_agenda' => 'Rapat Pertama',
            'tanggal_rapat' => date('Y-m-d', strtotime('+1 day')),
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '10:00',
            'lokasi' => 'Zoom',
            'dibuat_oleh' => $this->user->id_user,
        ]);

        // Second agenda on the same day
        $response = $this->actingAs($this->user)->post('/sekretaris/agenda', [
            'judul_agenda' => 'Rapat Kedua',
            'tanggal_rapat' => date('Y-m-d', strtotime('+1 day')),
            'waktu_mulai' => '13:00',
            'waktu_selesai' => '14:00',
            'lokasi' => 'Zoom',
        ]);

        $response->assertSessionHasErrors('tanggal_rapat');
    }

    public function test_can_update_agenda_on_same_day_without_self_conflict(): void
    {
        $agenda = AgendaRapat::create([
            'judul_agenda' => 'Rapat Pertama',
            'tanggal_rapat' => date('Y-m-d', strtotime('+1 day')),
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '10:00',
            'lokasi' => 'Zoom',
            'dibuat_oleh' => $this->user->id_user,
        ]);

        $response = $this->actingAs($this->user)->put("/sekretaris/agenda/{$agenda->id_agenda}", [
            'judul_agenda' => 'Rapat Pertama Terupdate',
            'tanggal_rapat' => $agenda->tanggal_rapat,
            'waktu_mulai' => '10:00',
            'waktu_selesai' => '11:00',
            'lokasi' => 'Zoom Baru',
        ]);

        $response->assertRedirect('/sekretaris/agenda');
        $this->assertDatabaseHas('agenda_rapat', [
            'id_agenda' => $agenda->id_agenda,
            'judul_agenda' => 'Rapat Pertama Terupdate',
        ]);
    }

    public function test_cannot_create_agenda_with_end_time_before_or_equal_to_start_time(): void
    {
        $response = $this->actingAs($this->user)->post('/sekretaris/agenda', [
            'judul_agenda' => 'Rapat Waktu Terbalik',
            'tanggal_rapat' => date('Y-m-d', strtotime('+1 day')),
            'waktu_mulai' => '10:00',
            'waktu_selesai' => '09:00',
            'lokasi' => 'Zoom',
        ]);

        $response->assertSessionHasErrors('waktu_selesai');

        $response2 = $this->actingAs($this->user)->post('/sekretaris/agenda', [
            'judul_agenda' => 'Rapat Waktu Sama',
            'tanggal_rapat' => date('Y-m-d', strtotime('+1 day')),
            'waktu_mulai' => '10:00',
            'waktu_selesai' => '10:00',
            'lokasi' => 'Zoom',
        ]);

        $response2->assertSessionHasErrors('waktu_selesai');
    }
}
