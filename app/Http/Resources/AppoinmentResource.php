<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppoinmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'phone' => $this->phone,
          'email' => $this->email,
          'service_id' => $this->service_id,
          'service' => new ServiceResource($this->service),
          'analyst' => new AnalystResource($this->analyst),
        ];
        //return parent::toArray($request);
    }
}
