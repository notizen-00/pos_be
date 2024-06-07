<?php
namespace App\Filament\Resources\TransaksiResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\TransaksiResource;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        DB::transaction(function () use ($request, &$transaksi) {
            // Create a new Transaksi model instance
            $transaksi = new Transaksi();
            

            $transaksi->fill($request->transaksi);

            if(isset($request->transaksi['created_at'])){
                $transaksi->created_at = Carbon::now();
            }else{
                $transaksi->created_at = Carbon::now();
            }

            $transaksi->save();
    
            
            if ($transaksi) {
                // Get the ID of the inserted transaksi
                $transaksi_id = $transaksi->id;
    
                // Iterate over each detail_transaksi entry
                foreach ($request->detail_transaksi as $detail) {
                    // Add the transaksi_id to each detail_transaksi entry
                    $detail['transaksi_id'] = $transaksi_id;
                    // Create a new DetailTransaksi entry
                    DetailTransaksi::create($detail);
                }
            }
        });
    
        if ($transaksi) {
            return static::sendSuccessResponse($transaksi, 'Transaksi berhasil di simpan pada '.Carbon::now());
        } else {
            return static::sendServerErrorResponse('message', 'Failed to create resource');
        }
        
    }
}