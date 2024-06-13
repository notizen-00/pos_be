<?php

namespace App\Filament\Clusters\Inventori\Resources\StokKeluarResource\Pages;

use App\Filament\Clusters\Inventori\Resources\StokKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStokKeluar extends EditRecord
{
    protected static string $resource = StokKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
