<?php
namespace App\Filament\Resources\ProdukResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\ProdukResource;
use Illuminate\Routing\Router;


class ProdukApiService extends ApiService
{
    protected static string | null $resource = ProdukResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
