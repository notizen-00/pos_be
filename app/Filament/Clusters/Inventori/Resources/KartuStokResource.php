<?php

namespace App\Filament\Clusters\Inventori\Resources;

use App\Filament\Clusters\Inventori;
use App\Filament\Clusters\Inventori\Resources\KartuStokResource\Pages;
use App\Filament\Clusters\Inventori\Resources\KartuStokResource\RelationManagers;
use Illuminate\Database\Eloquent\Model;
use App\Models\KartuStok;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Pages\SubNavigationPosition;
use App\Models\StokMasuk;
use App\Models\DetailPembelian;
class KartuStokResource extends Resource
{
    protected static ?string $model = KartuStok::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Inventori::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_bahan')
                ->label('Bahan')
                ->searchable(),
                Tables\Columns\TextColumn::make('stok_masuk_detail')
                ->formatStateUsing(function (Model $model) {
                    return $model->stok_masuk_detail->sum('quantity') ?? 0;
                })
                ->default(0)
                ->label('Stok Masuk')
                ,
                Tables\Columns\TextColumn::make('stok_keluar')
                ->default(0)
                ->label('Stok Keluar'),
                Tables\Columns\TextColumn::make('stok_keluar_detail')
                ->formatStateUsing(function (Model $model) {
                    return $model->stok_keluar_detail->sum('quantity') ?? 0;
                })
                ->default(0)
                ->color('danger')
                ->label('Penjualan'),
                Tables\Columns\TextColumn::make('stok_akhir')
                ->label('Stok Akhir')
                ->default(function (Model $model) {
                    $stokMasuk = $model->stok_masuk_detail->sum('quantity') ?? 0;
                    $stokKeluar = $model->stok_keluar_detail->sum('quantity') ?? 0;
                    return $stokMasuk - $stokKeluar;
                }),
                Tables\Columns\TextColumn::make('satuan')
                ->label('Satuan')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->query(function () {
                return KartuStok::query()
                    ->with(['stok_masuk_detail','stok_keluar_detail']);
            })
            ->striped();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKartuStoks::route('/'),
            'create' => Pages\CreateKartuStok::route('/create'),
            'edit' => Pages\EditKartuStok::route('/{record}/edit'),
        ];
    }
}
