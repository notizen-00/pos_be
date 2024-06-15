<?php
namespace App\Filament\Resources\TransaksiResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\TransaksiResource;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\StokKeluar;
use App\Models\Resep;
use App\Models\Bahan;
use App\Models\DetailPembelian;
use App\Models\DetailPenjualan;
use Illuminate\Database\Eloquent\Model;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = TransaksiResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    public function handler(Request $request)
    {
        $transaksi = null;
        $error = null;

        try {
            DB::transaction(function () use ($request, &$transaksi) {
                $transaksi = new Transaksi();

                $transaksi->fill($request->transaksi);

                if(isset($request->transaksi['created_at'])){
                    $transaksi->created_at = Carbon::now();
                }else{
                    $transaksi->created_at = Carbon::now();
                }

                $transaksi->save();

                if ($transaksi) {
                    $transaksi_id = $transaksi->id;
                    
                    foreach ($request->detail_transaksi as $detail) {

                        $this->updateStokBahan($detail['produk_id'], $detail['quantity']);
                        $this->insertPenjualanBahan($transaksi, $detail['produk_id'], $detail['quantity']);
                        $detail['transaksi_id'] = $transaksi_id;
                        DetailTransaksi::create($detail);
                    }
                }
            });
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return static::sendFailResponse('Server mengalami Gangguan', $error);
        }
        
        if($transaksi) {
            return static::sendSuccessResponse($transaksi, 'Transaksi berhasil disimpan pada ' . Carbon::now());    
        }
        

    }
protected function updateStokBahan(int $produk_id, int $quantity)
{
    $resep = Resep::where('produk_id', $produk_id)->get();

    if ($resep->isEmpty()) {
        return static::sendNotFoundResponse("Resep untuk produk dengan ID {$produk_id} tidak ditemukan.");
    }
    foreach ($resep as $item) {
        $bahan = Bahan::find($item->bahan_produk_id);
        if ($bahan) {
            $bahan->stok -= $item->quantity_resep * $quantity;

            if ($bahan->stok < 0) {
                $error = 'Stok bahan ' . $bahan->nama_bahan . ' tidak mencukupi.';
                return static::sendFailResponse('Stok bahan ' . $bahan->nama_bahan . ' tidak mencukupi.', $error);
            }
            $bahan->save();
        } else {
                return static::sendNotFoundResponse('Bahan dengan ID ' . $item->bahan_produk_id . ' tidak ditemukan.');
        }
    }
}

protected function insertPenjualanBahan(Model $transaksi, int $produk_id, int $quantity)
{
    $resep = Resep::where('produk_id', $produk_id)->get();

    if ($resep->isEmpty()) {
        return static::sendNotFoundResponse("Resep untuk produk dengan ID {$produk_id} tidak ditemukan.");
    }

    $data_penjualan = [
        'transaksi_id' => $transaksi->id,
        'nomor_penjualan' => static::generateNomorPenjualan(),
        'total_penjualan' => $transaksi->total,
        'author_id' => auth()->user()->id,
        'tanggal_penjualan' => Carbon::now(),
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
                return static::sendNotFoundResponse("Bahan dengan ID {$item->bahan_produk_id} tidak ditemukan.");
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

    

}