<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Order;
use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    private function buatOrder(array $overrides = []): array
    {
        $member = Member::firstOrCreate(
            ['nik' => '3201234567890001'],
            ['nama' => 'Budi', 'tanggal_bergabung' => '2026-01-01']
        );

        return array_merge([
            'id_store' => '1',
            'kode_order' => 'ORD-001',
            'total_harga' => 30000,
            'diskon' => 5000,
            'member_id' => $member->id,
            'meja' => 'A1',
            'pembayaran' => 'tunai',
            'tanggal' => '2026-08-21',
        ], $overrides);
    }

    public function test_bisa_mengambil_daftar_order_dengan_item(): void
    {
        $kategori = Kategori::create(['nama' => 'Makanan']);
        $produk = Produk::create(['nama' => 'Nasi Goreng', 'kategori_id' => $kategori->id, 'harga' => 15000]);
        $kasir = Pengguna::create(['nama' => 'Kasir', 'username' => 'kasir', 'password' => 'rahasia123']);

        Order::create($this->buatOrder());
        Pesanan::create([
            'kode_order' => 'ORD-001',
            'produk_id' => $produk->id,
            'harga' => 15000,
            'qty' => 2,
            'diskon' => 0,
            'tanggal' => '2026-08-21',
            'kasir_id' => $kasir->id,
            'pembayaran' => 'tunai',
        ]);

        $this->getJson('/api/order')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.kode_order', 'ORD-001')
            ->assertJsonPath('data.0.member.nama', 'Budi')
            ->assertJsonPath('data.0.jumlah_item', 2)
            ->assertJsonPath('data.0.items.0.produk.nama', 'Nasi Goreng');
    }

    public function test_bisa_menambahkan_order(): void
    {
        $this->postJson('/api/order', $this->buatOrder())
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.kode_order', 'ORD-001')
            ->assertJsonPath('data.pembayaran', 'tunai');

        $this->assertDatabaseHas('order', ['kode_order' => 'ORD-001', 'id_store' => '1']);
    }

    public function test_validasi_kode_order_wajib_dan_unik(): void
    {
        Order::create($this->buatOrder());

        $this->postJson('/api/order', $this->buatOrder(['meja' => 'A2']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['kode_order']);

        $this->postJson('/api/order', $this->buatOrder(['kode_order' => '', 'pembayaran' => 'kartu']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['kode_order'])
            ->assertJsonMissingValidationErrors(['pembayaran']);
    }

    public function test_pembayaran_menerima_nilai_bebas(): void
    {
        $this->postJson('/api/order', $this->buatOrder(['pembayaran' => 'kartu debit']))
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.pembayaran', 'kartu debit');
    }

    public function test_uang_dan_kembalian_bisa_disimpan(): void
    {
        $this->postJson('/api/order', $this->buatOrder([
            'uang' => 50000,
            'kembalian' => 20000,
        ]))
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.uang', 50000)
            ->assertJsonPath('data.kembalian', 20000);

        $this->assertDatabaseHas('order', [
            'kode_order' => 'ORD-001',
            'uang' => 50000,
            'kembalian' => 20000,
        ]);
    }

    public function test_bisa_mengubah_order(): void
    {
        $order = Order::create($this->buatOrder());

        $this->putJson("/api/order/{$order->id}", $this->buatOrder([
            'total_harga' => 45000,
            'pembayaran' => 'qris',
        ]))
            ->assertOk()
            ->assertJsonPath('data.total_harga', 45000)
            ->assertJsonPath('data.pembayaran', 'qris');
    }

    public function test_bisa_menghapus_order(): void
    {
        $order = Order::create($this->buatOrder());

        $this->deleteJson("/api/order/{$order->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('order', ['id' => $order->id]);
    }

    private function buatOrderItem(int $produkId, int $qty, string $jenisUsaha = 'Toko', string $kodeOrder = 'ORD-001', ?string $satuan = null): void
    {
        User::factory()->create(['id' => 1, 'jenis_usaha' => $jenisUsaha]);

        $this->kirimOrderItem($produkId, $qty, $kodeOrder, $satuan);
    }

    private function kirimOrderItem(int $produkId, int $qty, string $kodeOrder = 'ORD-001', ?string $satuan = null): void
    {
        Order::create($this->buatOrder(['kode_order' => $kodeOrder]));

        $payload = [
            'id_store' => '1',
            'kode_order' => $kodeOrder,
            'produk_id' => $produkId,
            'harga' => 5000,
            'qty' => $qty,
            'tanggal' => '2026-09-12',
        ];

        if ($satuan) {
            $payload['satuan'] = $satuan;
        }

        $this->postJson('/api/order-items', $payload)
            ->assertCreated()->assertJsonPath('success', true);
    }

    private function buatProduk(array $overrides = []): Produk
    {
        $kategori = Kategori::create(['nama' => 'Sembako']);

        return Produk::create(array_merge([
            'nama' => 'Sabun',
            'kategori_id' => $kategori->id,
            'harga' => 5000,
            'qty_all' => 100,
        ], $overrides));
    }

    public function test_order_item_mengurangi_qty_all_produk(): void
    {
        $produk = $this->buatProduk();

        $this->buatOrderItem($produk->id, 3);

        $this->assertSame(97, Produk::find($produk->id)->qty_all);
    }

    public function test_qty_all_tidak_bisa_negatif(): void
    {
        $produk = $this->buatProduk(['qty_all' => 2]);

        $this->buatOrderItem($produk->id, 5);

        $this->assertSame(0, Produk::find($produk->id)->qty_all);
    }

    public function test_qty_all_null_tidak_berubah_saat_order_item_dibuat(): void
    {
        $produk = $this->buatProduk(['qty_all' => null]);

        $this->buatOrderItem($produk->id, 2);

        $this->assertNull(Produk::find($produk->id)->qty_all);
    }

    public function test_usaha_bukan_toko_tidak_mengurangi_qty_all(): void
    {
        $produk = $this->buatProduk();
        User::factory()->create(['id' => 1, 'jenis_usaha' => 'Restoran']);

        $this->kirimOrderItem($produk->id, 3, 'ORD-002');
        $this->kirimOrderItem($produk->id, 2, 'ORD-003');

        $this->assertSame(100, Produk::find($produk->id)->qty_all);
    }

    public function test_pembelian_satuan_besar_mengurangi_qty_all_dan_qty(): void
    {
        $produk = $this->buatProduk(['qty' => 2, 'isi' => 12, 'qty_all' => 24]);

        $this->buatOrderItem($produk->id, 1, 'Toko', 'ORD-004', 'besar');

        $produkBaru = Produk::find($produk->id);
        $this->assertSame(12, $produkBaru->qty_all);
        $this->assertSame(1, $produkBaru->qty);
    }

    public function test_pembelian_satuan_kecil_mengurangi_qty_all_dan_qty(): void
    {
        $produk = $this->buatProduk(['qty' => 2, 'isi' => 12, 'qty_all' => 24]);

        $this->buatOrderItem($produk->id, 6, 'Toko', 'ORD-005', 'kecil');

        $produkBaru = Produk::find($produk->id);
        $this->assertSame(18, $produkBaru->qty_all);
        $this->assertSame(1, $produkBaru->qty);
    }

    public function test_satuan_tanpa_field_default_kecil(): void
    {
        $produk = $this->buatProduk(['qty' => 2, 'isi' => 12, 'qty_all' => 24]);

        $this->buatOrderItem($produk->id, 12);

        $produkBaru = Produk::find($produk->id);
        $this->assertSame(12, $produkBaru->qty_all);
        $this->assertSame(1, $produkBaru->qty);
    }

    public function test_satuan_invalid_ditolak(): void
    {
        $produk = $this->buatProduk();
        User::factory()->create(['id' => 1, 'jenis_usaha' => 'Toko']);
        Order::create($this->buatOrder());

        $this->postJson('/api/order-items', [
            'id_store' => '1',
            'kode_order' => 'ORD-001',
            'produk_id' => $produk->id,
            'harga' => 5000,
            'qty' => 1,
            'tanggal' => '2026-09-12',
            'satuan' => 'ekor',
        ])->assertStatus(422)->assertJsonValidationErrors(['satuan']);
    }

    public function test_update_item_mengembalikan_selisih_stok(): void
    {
        $produk = $this->buatProduk(['qty' => 2, 'isi' => 12, 'qty_all' => 24]);
        $this->buatOrderItem($produk->id, 1, 'Toko', 'ORD-004', 'besar');

        $item = Pesanan::first();
        $this->assertSame(12, Produk::find($produk->id)->qty_all);

        $this->putJson("/api/order-items/{$item->id}", [
            'id_store' => '1',
            'kode_order' => 'ORD-004',
            'produk_id' => $produk->id,
            'harga' => 5000,
            'qty' => 2,
            'satuan' => 'kecil',
            'tanggal' => '2026-09-12',
        ])->assertOk();

        $this->assertSame(22, Produk::find($produk->id)->qty_all);
    }
}
