<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\MaxWidth;
use App\Models\Resep;
use App\Models\Bahan;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\StokKeluar;
use App\Models\DetailPenjualan;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailPembelian;

class CreateTransaksi extends CreateRecord
{
    protected static string $resource = TransaksiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['author_id'] = auth()->id();
    $data['status'] = 'closed';
    $data['shift_id'] = 1;  
    $data['metode_pembayaran'] = 'tunai';
    
    return $data;
}

protected function handleRecordCreation(array $data): Model
{

    DB::beginTransaction();

    try {

        $transaksi = static::getModel()::create($data);


        foreach ($data['detail_transaksi'] as $i) {
            $this->updateStokBahan($i['produk_id'], $i['quantity']);
            $this->insertPenjualanBahan($transaksi, $i['produk_id'], $i['quantity']);
        }

        DB::commit();
    } catch (Exception $e) {

        DB::rollBack();
        throw $e;
    }

    return $transaksi;
}

protected function updateStokBahan(int $produk_id, int $quantity): void
{
    $resep = Resep::where('produk_id', $produk_id)->get();

    foreach ($resep as $item) {
        $bahan = Bahan::find($item->bahan_produk_id);
        if ($bahan) {
            $bahan->stok -= $item->quantity_resep * $quantity;

            if ($bahan->stok < 0) {
                throw new Exception("Stok bahan {$bahan->nama_bahan} tidak mencukupi.");
            }
            $bahan->save();
        } else {
            throw new Exception("Bahan dengan ID {$item->bahan_produk_id} tidak ditemukan.");
        }
    }
}

protected function insertPenjualanBahan(Model $transaksi, int $produk_id, int $quantity): void
{
    $resep = Resep::where('produk_id', $produk_id)->get();

    if ($resep->isEmpty()) {
        throw new Exception("Resep untuk produk dengan ID {$produk_id} tidak ditemukan.");
    }

    $data_penjualan = [
        'transaksi_id' => $transaksi->id,
        'nomor_penjualan' => static::generateNomorPenjualan(),
        'total_penjualan' => $transaksi->total,
        'author_id' => auth()->user()->id,
        'deskripsi' => 'Penjualan Transaksi ' . $transaksi->nomor_transaksi,
    ];

    $stok_keluar = StokKeluar::create($data_penjualan);

    if ($stok_keluar) {
        $detail_penjualan_data = [];

        foreach ($resep as $item) {
            $bahan = Bahan::find($item->bahan_produk_id);
            if ($bahan) {
                $averageHargaBeli = DetailPembelian::getAverageHargaBeli($item->bahan_produk_id);
                $detail_penjualan_data[] = [
                    'penjualan_id' => $stok_keluar->id,
                    'bahan_produk_id' => $item->bahan_produk_id,
                    'quantity' => $item->quantity_resep * $quantity,
                    'harga_asset' => $averageHargaBeli,
                    'subtotal' => $averageHargaBeli * $item->quantity_resep * $quantity,
                ];
            } else {
                throw new Exception("Bahan dengan ID {$item->bahan_produk_id} tidak ditemukan.");
            }
        }

        DetailPenjualan::insert($detail_penjualan_data);
    }
}

protected static function generateNomorPenjualan(): string
{
    $latestStokKeluar = StokKeluar::latest()->first();
    $latestId = $latestStokKeluar ? $latestStokKeluar->id + 1 : 1;
    return sprintf('SK-%06d', $latestId);
}

protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
    public function getMaxContentWidth(): MaxWidth
{
    return MaxWidth::Full;
}
}
