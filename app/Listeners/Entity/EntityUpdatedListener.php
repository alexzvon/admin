<?php

namespace App\Listeners\Entity;

use App\Events\Entity\EntityUpdated;

class EntityUpdatedListener
{
    /**
     * Handle the event.
     *
     * @param  EntityUpdated  $event
     * @return void
     */
    public function handle(EntityUpdated $event)
    {
        \App\Http\Controllers\DataHandler::clearCache($event->getModelClassName());
    }
}
