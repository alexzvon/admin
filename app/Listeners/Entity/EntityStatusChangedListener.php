<?php

namespace App\Listeners\Entity;

use App\Events\Entity\EntityStatusChanged;

class EntityStatusChangedListener
{
    /**
     * Handle the event.
     *
     * @param  CategoryUpdated  $event
     * @return void
     */
    public function handle(EntityStatusChanged $event)
    {
        \App\Http\Controllers\DataHandler::clearCache($event->getModelClassName());
    }
}
