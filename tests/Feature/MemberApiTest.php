<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_bisa_mengambil_daftar_member(): void
    {
        Member::create(['nama' => 'Budi', 'nik' => '3201234567890001', 'tanggal_bergabung' => '2026-01-01']);
        Member::create(['nama' => 'Siti', 'nik' => '3201234567890002', 'tanggal_bergabung' => '2026-02-01']);

        $this->getJson('/api/member')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['nama' => 'Budi']);
    }

    public function test_bisa_menambahkan_member(): void
    {
        $this->postJson('/api/member', [
            'nama' => 'Budi',
            'nik' => '3201234567890001',
            'alamat' => 'Jl. Merdeka No. 10',
            'tanggal_bergabung' => '2026-01-01',
        ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.nama', 'Budi');

        $this->assertDatabaseHas('member', ['nik' => '3201234567890001']);
    }

    public function test_validasi_nik_wajib_dan_unik(): void
    {
        Member::create(['nama' => 'Budi', 'nik' => '3201234567890001', 'tanggal_bergabung' => '2026-01-01']);

        $this->postJson('/api/member', ['nama' => 'Siti', 'nik' => '', 'tanggal_bergabung' => '2026-02-01'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);

        $this->postJson('/api/member', ['nama' => 'Siti', 'nik' => '3201234567890001', 'tanggal_bergabung' => '2026-02-01'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    public function test_bisa_mengubah_member(): void
    {
        $member = Member::create(['nama' => 'Budi', 'nik' => '3201234567890001', 'tanggal_bergabung' => '2026-01-01']);

        $this->putJson("/api/member/{$member->id}", [
            'nama' => 'Budi Santoso',
            'nik' => '3201234567890001',
            'tanggal_bergabung' => '2026-01-01',
        ])
            ->assertOk()
            ->assertJsonPath('data.nama', 'Budi Santoso');
    }

    public function test_bisa_menghapus_member(): void
    {
        $member = Member::create(['nama' => 'Budi', 'nik' => '3201234567890001', 'tanggal_bergabung' => '2026-01-01']);

        $this->deleteJson("/api/member/{$member->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('member', ['id' => $member->id]);
    }
}
