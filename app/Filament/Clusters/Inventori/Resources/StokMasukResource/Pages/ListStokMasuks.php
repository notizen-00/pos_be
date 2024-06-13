<?php

namespace App\Filament\Clusters\Inventori\Resources\StokMasukResource\Pages;

use App\Filament\Clusters\Inventori\Resources\StokMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStokMasuks extends ListRecords
{
    protected static string $resource = StokMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
