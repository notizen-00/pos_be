<?php

namespace App\Filament\Clusters\Produk\Resources\ProdukResource\Pages;

use App\Filament\Clusters\Produk\Resources\ProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduk extends EditRecord
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
