<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Anggota;
use App\Models\User;
use App\Models\StatusAbsensi;
use App\Models\AgendaRapat;
use App\Models\Absensi;
use App\Models\Izin;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Roles
        $roleSekretaris = Role::create(['name' => 'Sekretaris']);
        $roleAnggota = Role::create(['name' => 'Anggota']);

        // 2. Create Status Absensi
        $statusHadir = StatusAbsensi::create([
            'id_status' => 1,
            'nama_status' => 'Hadir',
            'bobot_kehadiran' => 1.00
        ]);
        $statusShift2Hadir = StatusAbsensi::create([
            'id_status' => 2,
            'nama_status' => 'Shift 2 Hadir Sebagian',
            'bobot_kehadiran' => 0.50
        ]);
        $statusShift2Absen = StatusAbsensi::create([
            'id_status' => 3,
            'nama_status' => 'Shift 2 Tidak Hadir Sama Sekali',
            'bobot_kehadiran' => 0.00
        ]);
        $statusIzin = StatusAbsensi::create([
            'id_status' => 4,
            'nama_status' => 'Izin',
            'bobot_kehadiran' => 0.75
        ]);

        // 3. Create Anggota & Users
        // Sekretaris
        $agtSekre = Anggota::create([
            'nim' => '0320210000',
            'nama_anggota' => 'Siti Aminah',
            'jenis_kelamin' => 'P',
            'email_astra' => 'siti@mpm.astra.ac.id',
            'no_hp' => '081234567890',
            'jabatan' => 'Sekretaris MPM',
            'angkatan' => 2024,
            'foto_anggota' => null,
            'status' => 'Aktif'
        ]);

        $userSekre = User::create([
            'username' => 'sekretaris',
            'password' => 'password', // Mutator or cast hashes this automatically if cast/mutator defined
            'role' => 'Sekretaris',
            'id_anggota' => $agtSekre->id_anggota
        ]);
        $userSekre->assignRole($roleSekretaris);

        // Anggota list
        $anggotaData = [
            [
                'nim' => '0320210001',
                'nama' => 'Ahmad Fauzi',
                'jk' => 'L',
                'email' => 'ahmad@mpm.astra.ac.id',
                'hp' => '081345678901',
                'jabatan' => 'Ketua MPM',
                'angkatan' => 2024,
                'username' => 'ahmad'
            ],
            [
                'nim' => '0320210002',
                'nama' => 'Budi Santoso',
                'jk' => 'L',
                'email' => 'budi@mpm.astra.ac.id',
                'hp' => '081456789012',
                'jabatan' => 'Wakil Ketua MPM',
                'angkatan' => 2024,
                'username' => 'budi'
            ],
            [
                'nim' => '0320210003',
                'nama' => 'Citra Lestari',
                'jk' => 'P',
                'email' => 'citra@mpm.astra.ac.id',
                'hp' => '081567890123',
                'jabatan' => 'Ketua Komisi 1',
                'angkatan' => 2024,
                'username' => 'citra'
            ],
            [
                'nim' => '0320210004',
                'nama' => 'Dian Pratama',
                'jk' => 'L',
                'email' => 'dian@mpm.astra.ac.id',
                'hp' => '081678901234',
                'jabatan' => 'Anggota Komisi 2',
                'angkatan' => 2025,
                'username' => 'dian'
            ],
            [
                'nim' => '0320210005',
                'nama' => 'Eka Wijaya',
                'jk' => 'L',
                'email' => 'eka@mpm.astra.ac.id',
                'hp' => '081789012345',
                'jabatan' => 'Anggota Komisi 3',
                'angkatan' => 2025,
                'username' => 'eka'
            ]
        ];

        $members = [];
        foreach ($anggotaData as $data) {
            $agt = Anggota::create([
                'nim' => $data['nim'],
                'nama_anggota' => $data['nama'],
                'jenis_kelamin' => $data['jk'],
                'email_astra' => $data['email'],
                'no_hp' => $data['hp'],
                'jabatan' => $data['jabatan'],
                'angkatan' => $data['angkatan'],
                'foto_anggota' => null,
                'status' => 'Aktif'
            ]);

            $usr = User::create([
                'username' => $data['username'],
                'password' => 'password',
                'role' => 'Anggota',
                'id_anggota' => $agt->id_anggota
            ]);
            $usr->assignRole($roleAnggota);

            $members[] = $agt;
        }

        // 4. Create Agenda Rapat
        $agenda1 = AgendaRapat::create([
            'judul_agenda' => 'Rapat Koordinasi Awal Periode',
            'deskripsi' => 'Membahas program kerja umum dan pembagian divisi.',
            'tanggal_rapat' => '2026-05-10',
            'waktu_mulai' => '09:00:00',
            'waktu_selesai' => '11:30:00',
            'lokasi' => 'Aula Gedung B Lantai 2',
            'dibuat_oleh' => $userSekre->id_user
        ]);

        $agenda2 = AgendaRapat::create([
            'judul_agenda' => 'Rapat Rencana Anggaran Kegiatan',
            'deskripsi' => 'Finalisasi proposal anggaran untuk setiap kegiatan MPM setahun kedepan.',
            'tanggal_rapat' => '2026-05-24',
            'waktu_mulai' => '13:00:00',
            'waktu_selesai' => '15:30:00',
            'lokasi' => 'Ruang Rapat MPM',
            'dibuat_oleh' => $userSekre->id_user
        ]);

        $agenda3 = AgendaRapat::create([
            'judul_agenda' => 'Sidang Pleno Tengah Semester',
            'deskripsi' => 'Laporan pertanggungjawaban komisi-komisi.',
            'tanggal_rapat' => '2026-06-05',
            'waktu_mulai' => '09:00:00',
            'waktu_selesai' => '12:00:00',
            'lokasi' => 'Aula Gedung C Lantai 3',
            'dibuat_oleh' => $userSekre->id_user
        ]);

        $agenda4 = AgendaRapat::create([
            'judul_agenda' => 'Rapat Evaluasi Program Kerja Tahunan',
            'deskripsi' => 'Mengevaluasi keselarasan program kerja berjalan.',
            'tanggal_rapat' => '2026-06-15',
            'waktu_mulai' => '14:00:00',
            'waktu_selesai' => '16:00:00',
            'lokasi' => 'Ruang Rapat MPM',
            'dibuat_oleh' => $userSekre->id_user
        ]);

        // Upcoming Agenda
        AgendaRapat::create([
            'judul_agenda' => 'Rapat Sosialisasi Pemilu Anggota Baru',
            'deskripsi' => 'Pembentukan panitia pemilu MPM untuk periode selanjutnya.',
            'tanggal_rapat' => '2026-06-25',
            'waktu_mulai' => '10:00:00',
            'waktu_selesai' => '12:30:00',
            'lokasi' => 'Aula Utama Politeknik Astra',
            'dibuat_oleh' => $userSekre->id_user
        ]);

        // 5. Seed Attendance for Agendas 1, 2, 3, 4
        // Agenda 1
        $this->absenMember($agenda1, $members[0], $statusHadir); // Ahmad
        $this->absenMember($agenda1, $members[1], $statusHadir); // Budi
        $this->absenMember($agenda1, $members[2], $statusIzin, 'Sakit demam tinggi'); // Citra
        $this->absenMember($agenda1, $members[3], $statusShift2Hadir); // Dian
        $this->absenMember($agenda1, $members[4], $statusShift2Absen); // Eka

        // Agenda 2
        $this->absenMember($agenda2, $members[0], $statusHadir);
        $this->absenMember($agenda2, $members[1], $statusHadir);
        $this->absenMember($agenda2, $members[2], $statusHadir);
        $this->absenMember($agenda2, $members[3], $statusHadir);
        $this->absenMember($agenda2, $members[4], $statusHadir);

        // Agenda 3
        $this->absenMember($agenda3, $members[0], $statusHadir);
        $this->absenMember($agenda3, $members[1], $statusShift2Hadir);
        $this->absenMember($agenda3, $members[2], $statusHadir);
        $this->absenMember($agenda3, $members[3], $statusIzin, 'Menghadiri kuliah asisten praktikum');
        $this->absenMember($agenda3, $members[4], $statusHadir);

        // Agenda 4
        $this->absenMember($agenda4, $members[0], $statusHadir);
        $this->absenMember($agenda4, $members[1], $statusHadir);
        $this->absenMember($agenda4, $members[2], $statusHadir);
        $this->absenMember($agenda4, $members[3], $statusHadir);
        $this->absenMember($agenda4, $members[4], $statusHadir);
    }

    private function absenMember(AgendaRapat $agenda, Anggota $anggota, StatusAbsensi $status, $alasan = null)
    {
        $absen = Absensi::create([
            'id_agenda' => $agenda->id_agenda,
            'id_anggota' => $anggota->id_anggota,
            'id_status' => $status->id_status,
            'waktu_absen' => Carbon::parse($agenda->tanggal_rapat)->setTimeFromTimeString($agenda->waktu_mulai),
            'keterangan' => $alasan
        ]);

        if ($status->id_status == 4) {
            Izin::create([
                'id_absensi' => $absen->id_absensi,
                'alasan' => $alasan ?? 'Izin Rapat',
                'bukti_file' => null,
                'status_verifikasi' => 'Disetujui'
            ]);
        }
    }
}
