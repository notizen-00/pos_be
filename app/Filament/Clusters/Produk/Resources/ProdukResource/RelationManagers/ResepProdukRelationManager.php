<?php

namespace App\Filament\Clusters\Produk\Resources\ProdukResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\AttachAction;
use App\Models\Bahan;

class ResepProdukRelationManager extends RelationManager
{
    protected static string $relationship = 'bahan_produk';
    protected static ?string $title = 'Resep Produk';
    protected static ?string $badge = 'new';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('quantity_resep')
                    ->required()
                    ->integer()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('bahan_produk.nama_bahan')
            ->columns([
                Tables\Columns\TextColumn::make('nama_bahan'),
                Tables\Columns\TextColumn::make('quantity_resep')
                ->suffix(fn(Bahan $record) => ' '.$record->satuan),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                ->form(fn (AttachAction $action): array => [
                $action->getRecordSelect(),
                Forms\Components\TextInput::make('quantity_resep')->integer()->required(),
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
