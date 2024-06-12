<?php

namespace App\Filament\Clusters\Inventori\Resources\KartuStokResource\Pages;

use App\Filament\Clusters\Inventori\Resources\KartuStokResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKartuStoks extends ListRecords
{
    protected static string $resource = KartuStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
