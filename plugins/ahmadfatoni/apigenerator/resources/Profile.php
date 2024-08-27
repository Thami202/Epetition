<?php namespace AhmadFatoni\ApiGenerator\Resources;

use Illuminate\Http\Resources\Json\Resource as JsonResource;

/**
 * Resource
 */
class Profile extends JsonResource
{

    /**
	 * Indicates if the resource's collection keys should be preserved.
	 *
	 * @var bool
	 */
	protected $preserveKeys = true;

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
			'id_number' => $this->id_number,
			'phone_number' => $this->phone_number,
			'province' => $this->province,
			'municipality' => $this->municipality,
			'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
			'deleted_at' =>$this->deleted_at,
			                                                       
        ];
    }
}
