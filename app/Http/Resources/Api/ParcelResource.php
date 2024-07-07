<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ParcelResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                      => (int) $this->id,
            'parcel_no'               => $this->parcel_no,
            'customer_name'           => $this->customer_name,
            'customer_phone_number'   => $this->customer_phone_number,
            'customer_address'        => $this->customer_address,
            'status'                  => $this->status,
            'cod'                     => $this->cod_charge,
            'created_at'              => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at'              => $this->updated_at->format('d-m-Y H:i:s'),
        ];

    }
}
