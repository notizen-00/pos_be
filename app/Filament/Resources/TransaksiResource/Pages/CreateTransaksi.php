<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\MaxWidth;

class CreateTransaksi extends CreateRecord
{
    protected static string $resource = TransaksiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['author_id'] = auth()->id();
    $data['status'] = 'closed';
    $data['metode_pembayaran'] = 'tunai';
    return $data;
}

protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
    public function getMaxContentWidth(): MaxWidth
{
    return MaxWidth::Full;
}
}
