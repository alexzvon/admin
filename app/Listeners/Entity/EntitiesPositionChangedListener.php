<?php

namespace App\Listeners\Entity;

use App\Events\Entity\EntitiesPositionChanged;

class EntitiesPositionChangedListener
{
    /**
     * Handle the event.
     *
     * @param  EntitiesPositionChanged  $event
     * @return void
     */
    public function handle(EntitiesPositionChanged $event)
    {
        \App\Http\Controllers\DataHandler::clearCache($event->getModelClassName());
    }
}
