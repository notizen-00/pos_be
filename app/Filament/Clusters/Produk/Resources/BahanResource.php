<?php

namespace App\Filament\Clusters\Produk\Resources;

use App\Filament\Clusters\Produk;
use App\Filament\Clusters\Produk\Resources\BahanResource\Pages;
use App\Filament\Clusters\Produk\Resources\BahanResource\RelationManagers;
use App\Models\Bahan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Column;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Pages\SubNavigationPosition;

class BahanResource extends Resource
{
    protected static ?string $model = Bahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?string $cluster = Produk::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Bahan')
                ->description('Isikan data bahan ')
                ->schema([
                    Forms\Components\TextInput::make('nama_bahan')
                    ->required(),
                    Forms\Components\TextInput::make('satuan')
                    ->required(),

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_bahan')
                ->label('Nama Bahan'),
                Tables\Columns\TextColumn::make('satuan')
                ->label('Satuan'),
                Tables\Columns\TextColumn::make('stok')
                ->label('Stok')
                ->suffix(fn(Bahan $record) => ' '.$record->satuan),
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
            ]);
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
            'index' => Pages\ListBahans::route('/'),
            'create' => Pages\CreateBahan::route('/create'),
            'edit' => Pages\EditBahan::route('/{record}/edit'),
        ];
    }
}
