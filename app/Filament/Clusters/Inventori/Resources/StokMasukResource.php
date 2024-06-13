<?php

namespace App\Filament\Clusters\Inventori\Resources;

use App\Filament\Clusters\Inventori;
use App\Filament\Clusters\Inventori\Resources\StokMasukResource\Pages;
use App\Filament\Clusters\Inventori\Resources\StokMasukResource\RelationManagers;
use App\Models\StokMasuk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Bahan;
use Filament\Support\RawJs;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Support\Facades\DB;

class StokMasukResource extends Resource
{
    protected static ?string $model = StokMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?string $cluster = Inventori::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Pembelian')
                ->description('Detail Pembelian Stok Masuk ')
                ->columns(3)
                ->schema([
                    Forms\Components\Placeholder::make('users.name')
                            ->label('Author ')
                            ->content(fn (StokMasuk $record): ?string => $record->author->name),
                    Forms\Components\Placeholder::make('created_at')
                            ->label('Di buat : ')
                            ->content(fn (StokMasuk $record): ?string => $record->created_at?->diffForHumans()),
                    Forms\Components\Placeholder::make('updated_at')
                            ->label('Di update : ')
                            ->content(fn (StokMasuk $record): ?string => $record->updated_at?->diffForHumans()),
                ])
                ->visible(fn($operation)=>$operation == 'edit'),
                Forms\Components\Section::make('Stok Masuk')
                ->description('isikan data stok masuk sesuai bahan ')
                ->schema([
                    Forms\Components\DatePicker::make('tanggal_pembelian')
                    ->required(),
                    Forms\Components\TextInput::make('deskripsi')
                    ->label('Catatan'),
                    Forms\Components\Repeater::make('detail_pembelian')
                    ->label('Detail Stok Masuk')
                    ->relationship()
                    ->columns(4)
                    ->schema([
                        Forms\Components\Select::make('bahan_produk_id')
                            ->label('Bahan')
                            ->searchable()
                            ->live(onBlur:true)
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->options(Bahan::pluck('nama_bahan','id'))
                            ->afterStateUpdated(function(Set $set,Get $get){ $nama_bahan = Bahan::findOrFail($get('bahan_produk_id')); $set('nama_bahan',$nama_bahan->nama_bahan); })
                            ->required(),
                        Forms\Components\Hidden::make('nama_bahan'),
                        Forms\Components\TextInput::make('quantity')
                        ->dehydrated()
                        ->suffix(function(Get $get){ $bahan = Bahan::find($get('bahan_produk_id')); return $bahan&&$bahan->satuan ? $bahan->satuan : ''; })
                        ->integer()
                        ->live(onBlur:true)
                        ->afterStateUpdated(function(Get $get,Set $set,$state){
                            $harga_satuan = round($get('subtotal') / $state,2);  
                            $set('harga_beli',$harga_satuan);
                        })
                        ->required(),
                        Forms\Components\TextInput::make('subtotal')
                        ->label('Total harga beli')
                        ->required()
                        ->prefix('Rp.')
                        ->live(onBlur:true)
                        ->afterStateUpdated(function(Get $get,Set $set,$state){
                            $qty = $get('quantity');
                            $harga_satuan = round($state / $qty,2);
                            $set('harga_beli',$harga_satuan);
                        }),
                        Forms\Components\TextInput::make('harga_beli')
                        ->label('Harga Beli Per Satuan')
                        ->readonly()
                        ->required()
                        ->live(onBlur:true)
                        ->prefix('Rp. ')
                        ->afterStateUpdated(function(Get $get,Set $set,$state){
                            $qty = $get('quantity');
                            $total = round($qty * $state,2);
                            $set('subtotal',$total);
                        }),
                    
                    ])
                    ->dehydrated()
                    ->itemLabel(function ($state){ return $state['nama_bahan'] ?? null . $state['quantity'] ?? '';})
                    ->mutateRelationshipDataBeforeSaveUsing(fn (array $data) => $data)
                    ->collapsible(),
                    
                    Forms\Components\TextInput::make('total_pembayaran_int')
                    ->label('Total Pembayaran')
                    ->placeholder(function(Get $get,Set $set) {
                        $services = $get('detail_pembelian');
                        $sum = 0;
                        
                        foreach ($services as $service) {
                            $subtotal = $service['subtotal'];
                            $sum += intval($subtotal);
                        }
                        
                        $set('totals','Rp '. number_format($sum));
                        $set('total_pembelian',$sum);
                        return 'Rp ' . number_format($sum);
                    })
                    ->readonly(),
                    Forms\Components\Hidden::make('total_pembelian')


                    
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pembelian')
                ->label('Nomor Pembelian')
                ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                ->label('Catatan')
                ->searchable(),
                Tables\Columns\TextColumn::make('total_pembelian')
                ->label('Total Pembelian')
                ->money('IDR', locale: 'id')
                ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal Pembelian')
                ->dateTime()
                
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
            'index' => Pages\ListStokMasuks::route('/'),
            'create' => Pages\CreateStokMasuk::route('/create'),
            'edit' => Pages\EditStokMasuk::route('/{record}/edit'),
        ];
    }
}
