<?php namespace AhmadFatoni\ApiGenerator\Resources;

use Illuminate\Http\Resources\Json\Resource as JsonResource;

/**
 * Resource
 */
class Signature extends JsonResource
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
			'petition_id' => $this->petition_id,
			'name' => $this->name,
			'surname' => $this->surname,
			'email' => $this->email,
			'organisation' => $this->organisation,
			'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
			'deleted_at' =>$this->deleted_at,
			                                                       
        ];
    }
}
