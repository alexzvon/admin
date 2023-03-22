<?php

namespace App\Listeners\Entity;

use App\Events\Entity\EntityDeleted;

class EntityDeletedListener
{
    /**
     * Handle the event.
     *
     * @param  EntityDeleted  $event
     * @return void
     */
    public function handle(EntityDeleted $event)
    {
        \App\Http\Controllers\DataHandler::clearCache($event->getModelClassName());
    }
}
