<?php

namespace App\Filament\Clusters\Inventori\Resources\KartuStokResource\Pages;

use App\Filament\Clusters\Inventori\Resources\KartuStokResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKartuStok extends EditRecord
{
    protected static string $resource = KartuStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
