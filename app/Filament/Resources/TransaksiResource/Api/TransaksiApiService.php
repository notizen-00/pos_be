<?php
namespace App\Filament\Resources\TransaksiResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\TransaksiResource;
use Illuminate\Routing\Router;


class TransaksiApiService extends ApiService
{
    protected static string | null $resource = TransaksiResource::class;

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
