<?php

namespace App\Filament\Clusters\Produk\Resources\ProdukResource\Pages;

use App\Filament\Clusters\Produk\Resources\ProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProduks extends ListRecords
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
