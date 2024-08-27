<?php namespace AhmadFatoni\ApiGenerator\Resources;

use Illuminate\Http\Resources\Json\Resource as JsonResource;

/**
 * Resource
 */
class Petition extends JsonResource
{
/**
*Indicates if the resource's collection keys should be preserved.
*
*@var bool
*/
protected $preserveKeys = true;

/**
*Transform the resource into an array.
*
*@param Illuminate\Http\Request $request
*@return array
*/

// Petition resource
public function toArray($request)
{
    // Load the 'profile' relationship
    $this->load('user.profile');

    // Check if the 'profile' relationship is loaded and not null
    if ($this->user && $this->user->profile) {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'intended_to' => $this->intended_to,
            'comment' => $this->comment,
            'reason' => $this->reason,
            'status_id' => $this->status_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'reference_no_parliament' => $this->reference_no_parliament,
            'reference_no_public' => $this->reference_no_public,
            'deleted_at' => $this->deleted_at,
            'user_id' => $this->user_id,
            'signatures' => Signature::collection($this->signatures),
            'status' => $this->status,
            'checked' => $this->checked,
            'user' => $this->user->toArray(),
            'profile' => $this->user->profile->toArray(),
        ];
    }

    return parent::toArray($request);
}



}




