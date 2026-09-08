<?php

namespace Tests\Feature;

use App\Models\Ppn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PpnApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_bisa_mengambil_daftar_ppn(): void
    {
        Ppn::create(['persentase' => 11, 'aktif' => true]);
        Ppn::create(['persentase' => 12, 'aktif' => false]);

        $this->getJson('/api/ppn')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['persentase' => 11]);
    }

    public function test_bisa_menambahkan_ppn(): void
    {
        $this->postJson('/api/ppn', [
            'persentase' => 12,
        ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.persentase', 12);

        $this->assertDatabaseHas('ppn', ['persentase' => 12]);
    }

    public function test_validasi_persentase_wajib_dan_range(): void
    {
        $this->postJson('/api/ppn', ['persentase' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['persentase']);

        $this->postJson('/api/ppn', ['persentase' => 101])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['persentase']);
    }

    public function test_bisa_melihat_detail_ppn(): void
    {
        $ppn = Ppn::create(['persentase' => 11, 'aktif' => true]);

        $this->getJson("/api/ppn/detail/{$ppn->id}")
            ->assertOk()
            ->assertJsonPath('data.persentase', 11)
            ->assertJsonPath('data.aktif', true);
    }

    public function test_bisa_mengambil_ppn_per_store(): void
    {
        Ppn::create(['id_store' => '1', 'persentase' => 11, 'aktif' => true]);
        Ppn::create(['id_store' => '2', 'persentase' => 12, 'aktif' => false]);

        $this->getJson('/api/ppn/store/1')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id_store' => '1']);
    }

    public function test_bisa_mengubah_ppn(): void
    {
        $ppn = Ppn::create(['persentase' => 11, 'aktif' => true]);

        $this->putJson("/api/ppn/{$ppn->id}", ['persentase' => 12, 'aktif' => false])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.persentase', 12)
            ->assertJsonPath('data.aktif', false);
    }

    public function test_bisa_menghapus_ppn(): void
    {
        $ppn = Ppn::create(['persentase' => 11, 'aktif' => true]);

        $this->deleteJson("/api/ppn/{$ppn->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('ppn', ['id' => $ppn->id]);
    }
}
