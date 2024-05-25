<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
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
