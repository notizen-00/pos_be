<?php

namespace App\Filament\Clusters\Produk\Resources\ProdukResource\Pages;

use App\Filament\Clusters\Produk\Resources\ProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\KategoriProduk;
class ListProduks extends ListRecords
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Produk')
            ->icon('heroicon-o-plus'),

            Action::make('Tambah Kategori')
            ->form([
                TextInput::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->required(),
            ])
            ->color('info')
            ->icon('heroicon-m-plus')
            ->modalIcon('heroicon-m-wrench-screwdriver')
            ->modalIconColor('info')
            ->modalSubmitActionLabel('Simpan Data')
            ->action(function (array $data): void { 
                KategoriProduk::create($data);
            })
        ];
    }

    public function getTabs(): array
    {

            $kategori = KategoriProduk::all();
            $tabs = [];
            $tabs[] = Tab::make('Semua');
            $tabs[] = Tab::make('Favorit')
                        ->modifyQueryUsing(function (Builder $query) use ($kategori){
                            $query->where('favorit',true);
                        });
            foreach ($kategori as $kategori) {
                $tabs[] = Tab::make($kategori->nama_kategori)
                    ->modifyQueryUsing(function (Builder $query) use ($kategori) {
                        $query->whereHas('kategori', function (Builder $query) use ($kategori) {
                            $query->where('kategori_id', $kategori->id);
                            $query->whereNot('nama_kategori', 'kiloan');
                        });
                    });
            }
            return $tabs;
        
    }
}
