<?php

namespace App\Filament\Clusters\Inventori\Resources\StokMasukResource\Pages;

use App\Filament\Clusters\Inventori\Resources\StokMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\StokMasuk;
use Illuminate\Support\Facades\DB;
use App\Models\Bahan;
class CreateStokMasuk extends CreateRecord
{
    protected static string $resource = StokMasukResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['author_id'] = auth()->id();
    $data['nomor_pembelian'] = static::generateNomorPembelian();

        try {
            DB::beginTransaction();
                    
                    $detailPembelian = $data['detail_pembelian'];
                            foreach ($detailPembelian as $detail) {
                                $bahanId = $detail['bahan_produk_id'];
                                $quantity = $detail['quantity'];
                                $bahan = Bahan::findOrFail($bahanId);
                                $totalStokBaru = $bahan->stok + $quantity;
                                $bahan->update(['stok' => $totalStokBaru]);
                            }

                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            throw $e;
        }
                    
    return $data;
}

protected static function generateNomorPembelian(): string
{
    $latestStokMasuk = StokMasuk::latest()->first();
    $latestId = $latestStokMasuk ? $latestStokMasuk->id + 1 : 1;

    return sprintf('SM-%06d', $latestId);
}
}
