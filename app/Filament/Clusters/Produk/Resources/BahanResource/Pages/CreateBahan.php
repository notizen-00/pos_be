<?php

namespace App\Filament\Clusters\Produk\Resources\BahanResource\Pages;

use App\Filament\Clusters\Produk\Resources\BahanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBahan extends CreateRecord
{
    protected static string $resource = BahanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['stok'] = 0;
    return $data;
}

protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}
