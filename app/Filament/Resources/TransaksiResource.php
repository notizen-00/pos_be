<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Models\Transaksi;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    protected static ?string $navigationGroup = 'Manajemen Toko';
    protected static function formatNomorTransaksi(): string
    {
        $latestTransaksi = Transaksi::latest()->first();
        $latestNomorTransaksi = $latestTransaksi ? $latestTransaksi->id : 0;
        $nextNomorTransaksi = $latestNomorTransaksi + 1;
        $tanggalSekarang = now()->format('Ymd');
        return "TRX/" . str_pad($nextNomorTransaksi, 3, '0', STR_PAD_LEFT) . "/$tanggalSekarang";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Section::make('Transaksi')
                    ->columns(2)
                    ->schema([
                        Components\TextInput::make('nomor_transaksi')
                            ->readonly()
                            ->default(static::formatNomorTransaksi()),

                        Components\TextInput::make('nama_pelanggan')
                            ->placeholder('Masukkan nama pelanggan'),

                        Components\Textarea::make('deskripsi')
                            ->placeholder('Masukkan deskripsi transaksi'),


                        Components\Section::make('Detail Transaksi')
                            ->schema([
                                Components\Repeater::make('detail_transaksi')
                                ->relationship()
                                ->columns(4)
                                ->schema([
                                    Components\Select::make('produk_id')
                                        ->relationship('produk','nama')
                                        ->options(
                                            Produk::pluck('nama', 'id')
                                        )   
                                        ->label('Produk')
                                        ->reactive()
                                        ->distinct()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->afterStateUpdated(fn ($state,Set $set) => $set('harga',Produk::find($state)->harga ?? 0)),
    
                                    Components\TextInput::make('quantity')
                                        ->label('Qty')
                                        ->numeric()
                                        ->default(0)
                                        ->live(onBlur:true)
                                        ->afterStateUpdated(fn ( $state,Set $set,Get $get) => $set('subtotal',$state * $get('harga')) ),
    
                                    Components\TextInput::make('harga')
                                        ->label('Harga')
                                        ->disabled()
                                        ->dehydrated()
                                        ->default(0),
    
                                    Components\TextInput::make('subtotal')
                                        ->label('Subtotal')
                                        ->disabled()

                                        ->dehydrated()
                                        ->default(0),
                                ])
                                ->addActionLabel('Tambah Produk')
                                ->collapsible()
                                ->live()
                                ->itemLabel(fn (array $state): ?string => "Subtotal : Rp. ".number_format($state['subtotal']) ?? null)
                                
                            ]),

                        Components\Section::make('Pembayaran')
                            ->columns(2)
                            ->schema([
                                Components\TextInput::make('totals')
                                    ->label('Total Pembayaran')
                                    ->disabled()
                                    ->dehydrated()
                                    ->placeholder(function(Get $get,Set $set) {
                                        $services = $get('detail_transaksi');
                                        $sum = 0;
                                        
                                        foreach ($services as $service) {
                                            $subtotal = str_replace(['Rp ', ',', '.'], '', $service['subtotal']);
                                            $sum += intval($subtotal);
                                        }
                                        
                                        $set('totals','Rp '. number_format($sum));
                                        $set('total',$sum);
                                        $set('jumlah_pembayaran',$sum);
                                        // $to = $get('total');
                                        // dd($to);
                                        return 'Rp ' . number_format($sum);
                                    }),
                                Components\Hidden::make('total'),
                                Components\TextInput::make('pembayaran')
                                    ->label('Uang Dibayar')
                                    ->numeric()
                                    ->live(onBlur:true)
                                    ->afterStateUpdated(fn($state,Set $set,Get $get)=>($set('kembalian',$state - $get('total') )))
                                    ->default(0),

                                Components\TextInput::make('kembalian')
                                    ->label('Kembalian')
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(0),
                            ]),
                    ]),
            ]);
    }

    protected static function updateHargaDanSubtotal($component, $state,$set)
    {

        $produk = Produk::find($state);
        $qty = $component->getForm()->getComponent('qty')->getValue();
        $harga = $produk->harga;
        $subtotal = $harga * $qty;
        
        $component->getForm()->getComponent('harga')->set($harga);
        $component->getForm()->getComponent('subtotal')->setValue($subtotal);
        
        $total = collect($component->getForm()->getComponent('detail_transaksi')->getValue())
            ->map(fn ($detail) => $detail['subtotal'])
            ->sum();
        
        $component->getForm()->getComponent('total')->setValue($total);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_transaksi')
                ->badge()
                ->toggleable(),
                Tables\Columns\TextColumn::make('nama_pelanggan')
                ->searchable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('user.name')
                ->label('Kasir')
                ->searchable()
                ->badge()
                ->icon('heroicon-m-sparkles')
                ->toggleable()
                ,
                Tables\Columns\TextColumn::make('status')
                ->sortable()
                ->badge()
                ->color('info'),
                
                Tables\Columns\TextColumn::make('pembayaran')
                ->sortable()
                ->money('IDR')
                ->toggleable(),
                Tables\Columns\TextColumn::make('total')
                ->sortable()
                ->money('IDR')
                ->toggleable(), 
                Tables\Columns\TextColumn::make('kembalian')
                ->sortable()
                ->money('IDR')
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

    public static function getPages(): array
    {
        return [
            
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
