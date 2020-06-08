<?php

namespace App\Observers;

use App\Media;

class MediaObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param  \App\Media  $order
     * @return void
     */
    public function created(Media $media)
    {
        //
    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Media  $order
     * @return void
     */
    public function updated(Media $media)
    {
        //
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\Media  $order
     * @return void
     */
    public function deleted(Media $media)
    {
        $media->deleteFile();
    }

    /**
     * Handle the order "restored" event.
     *
     * @param  \App\Media  $order
     * @return void
     */
    public function restored(Media $media)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\Media  $order
     * @return void
     */
    public function forceDeleted(Media $media)
    {
        //
    }
}
