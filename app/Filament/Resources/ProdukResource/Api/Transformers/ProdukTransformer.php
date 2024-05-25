<?php
namespace App\Filament\Resources\ProdukResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProdukTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
