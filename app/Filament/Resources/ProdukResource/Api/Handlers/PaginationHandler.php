<?php
namespace App\Filament\Resources\ProdukResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\ProdukResource;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = ProdukResource::class;


    public function handler()
    {
        $query = static::getEloquentQuery();
        $model = static::getModel();
        $perPage = min(request()->query('per_page', 100), 100);
        $query = QueryBuilder::for($query)
        ->allowedFields($model::$allowedFields ?? [])
        ->allowedSorts($model::$allowedSorts ?? [])
        ->allowedFilters($model::$allowedFilters ?? [])
        ->allowedIncludes($model::$allowedIncludes ?? null)
        ->paginate($perPage)
        ->appends(request()->query());

        return static::getApiTransformer()::collection($query);
    }
}
