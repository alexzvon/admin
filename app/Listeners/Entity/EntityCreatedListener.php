<?php

namespace App\Listeners\Entity;

use App\Events\Entity\EntityCreated;

class EntityCreatedListener
{
    /**
     * Handle the event.
     *
     * @param  EntityCreated  $event
     * @return void
     */
    public function handle(EntityCreated $event)
    {
        \App\Http\Controllers\DataHandler::clearCache($event->getModelClassName());
    }
}
