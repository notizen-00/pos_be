<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Filament\Resources\ProdukResource\RelationManagers;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    
    protected static ?string $navigationGroup = 'Manajemen Toko';

    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->description('Isikan form produk dengan benar !')
                ->icon('heroicon-o-archive-box')
                ->schema([
                    Forms\Components\TextInput::make('nama')
                    ->label('Nama Produk')
                    ->required(),
                    Forms\Components\TextInput::make('harga')
                    ->label('Harga Produk')
                    ->placeholder(',00')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->required()
                    ->prefix('Rp. '),
                    Forms\Components\Select::make('kategori_id')
                    ->label('Kategori Produk')
                    ->relationship('kategori','nama_kategori')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nama_kategori')
                        ->required()
                    ]),
                    Forms\Components\TextInput::make('deskripsi')
                    ->label('Deskripsi (optional)'),
                    Forms\Components\FileUpload::make('foto')
                    ->label('Foto Produk (optional)')
                    ->directory('foto_produk')
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png','image/webp'])
                    ->imagePreviewHeight('150')
                    ->loadingIndicatorPosition('left')
                    ->panelAspectRatio('2:1')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadButtonPosition('left')
                    ->uploadProgressIndicatorPosition('left')
                
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                ->toggleable(),
                Tables\Columns\TextColumn::make('nama')
                ->searchable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                ->searchable()
                ->badge()
                ->icon('heroicon-m-sparkles')
                ->toggleable()
                ,
                Tables\Columns\TextColumn::make('harga')
                ->sortable()
                ->money('IDR')
                ->toggleable(),
                Tables\Columns\ToggleColumn::make('status')
                ->sortable()
                ->toggleable(),
                
                Tables\Columns\ToggleColumn::make('favorit')
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal Di buat')
                ->dateTime()
                ->sortable()
                ->toggleable()
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}
