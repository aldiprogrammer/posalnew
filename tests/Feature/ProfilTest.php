<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use App\Models\Profil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Pengguna::factory()->create());
    }

    public function test_halaman_profil_tampil_dengan_form(): void
    {
        $this->get(route('admin.profil.edit'))
            ->assertOk()
            ->assertSee('Nama Usaha')
            ->assertSee('Logo Usaha')
            ->assertSee('No. HP')
            ->assertSee('Alamat');
    }

    public function test_bisa_menyimpan_profil_baru_dengan_logo(): void
    {
        Storage::fake('public');

        $response = $this->put(route('admin.profil.update'), [
            'nama_usaha' => 'Warung Barokah',
            'nohp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 10',
            'logo' => UploadedFile::fake()->image('logo.png'),
        ]);

        $response->assertRedirect(route('admin.profil.edit'))
            ->assertSessionHas('success');

        $profil = Profil::first();
        $this->assertEquals('Warung Barokah', $profil->nama_usaha);
        $this->assertEquals('081234567890', $profil->nohp);
        $this->assertEquals('Jl. Merdeka No. 10', $profil->alamat);
        $this->assertNotNull($profil->logo);

        Storage::disk('public')->assertExists($profil->logo);
    }

    public function test_logo_lama_terhapus_ketika_ganti_logo(): void
    {
        Storage::fake('public');
        $logoLama = UploadedFile::fake()->image('lama.png')->store('profil', 'public');
        Profil::create(['nama_usaha' => 'Warung Barokah', 'logo' => $logoLama]);

        $this->put(route('admin.profil.update'), [
            'nama_usaha' => 'Warung Barokah',
            'logo' => UploadedFile::fake()->image('baru.png'),
        ]);

        Storage::disk('public')->assertMissing($logoLama);
        $this->assertNotEquals($logoLama, Profil::first()->logo);
    }

    public function test_validasi_nama_usaha_wajib_diisi(): void
    {
        $this->put(route('admin.profil.update'), ['nama_usaha' => ''])
            ->assertSessionHasErrors('nama_usaha');
    }
}
