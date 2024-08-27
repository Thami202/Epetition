<?php namespace AhmadFatoni\ApiGenerator\Classes;
 
use Queue;

class MediaEventHandler {
	/**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen('conversation.upload_media', 'AhmadFatoni\ApiGenerator\Classes\MediaEventHandler@downloadOrUpload');
    }

    /**
     * Handle model file attachment uploads
     */
    public function downloadOrUpload($event)
    {
        Queue::push(\AhmadFatoni\ApiGenerator\Classes\Jobs\DocumentUploaded::class, ['modelId' => $event->chatable_id, 'activity' => $event->getMediaFields()]);
    }
}