<?php namespace AhmadFatoni\ApiGenerator\Models;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
/**
 * Model
 */
class PetitionCreated extends Mailable
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    use Queueable, SerializesModels;

    public $petitionTitle;

    public function __construct($petitionTitle)
    {
        $this->petitionTitle = $petitionTitle;
    }

    public function build()
    {
        return $this->subject('New petition created: '.$this->petitionTitle)
                    ->view('emails.petition-created');
    }

}



