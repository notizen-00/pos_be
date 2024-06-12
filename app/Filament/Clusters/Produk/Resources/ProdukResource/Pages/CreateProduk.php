<?php

namespace App\Filament\Clusters\Produk\Resources\ProdukResource\Pages;

use App\Filament\Clusters\Produk\Resources\ProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduk extends CreateRecord
{
    protected static string $resource = ProdukResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['favorit'] = false;
        $data['status'] = true;
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
