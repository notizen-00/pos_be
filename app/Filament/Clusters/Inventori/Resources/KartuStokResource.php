<?php

namespace App\Filament\Clusters\Inventori\Resources;

use App\Filament\Clusters\Inventori;
use App\Filament\Clusters\Inventori\Resources\KartuStokResource\Pages;
use App\Filament\Clusters\Inventori\Resources\KartuStokResource\RelationManagers;
use App\Models\KartuStok;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KartuStokResource extends Resource
{
    protected static ?string $model = KartuStok::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Inventori::class;

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
                //
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
            'index' => Pages\ListKartuStoks::route('/'),
            'create' => Pages\CreateKartuStok::route('/create'),
            'edit' => Pages\EditKartuStok::route('/{record}/edit'),
        ];
    }
}
