<?php

namespace App\Listeners\Entity;

use Auth;
use App\Contracts\Events\EntityChangeEvent;
use App\Models\AdminLog;

class EntityChangeNeedsLogListener
{
    protected $event;
    protected $eventName;
    protected $entityName;
    protected $model;

    public function __construct()
    {
        $this->messages = \Lang::get('logs');
    }

    /**
     * Handle the event.
     *
     * @param  EntityChangeEvent  $event
     * @return void
     */
    public function handle(EntityChangeEvent $event)
    {
        $this->event = $event;
        $this->model = $event->getModel();
        $this->eventName = $event->getName();
        $this->entityName = $event->getEntityName();

        Auth::guard()->user()->adminLog()->save(new AdminLog([
            'type' => $this->getType(),
            'message' => $this->getMessage()
        ]));
    }

    protected function getType()
    {
        return $this->entityName . ucfirst($this->eventName);
    }

    protected function getMessage()
    {
        $messageLabel = 'logs.' . $this->getType();
        $data = $this->model->toArray();
        $message = \Lang::get($messageLabel, $data);

        if (is_array($message)) {
            reset($message);

            $messageLabel = $messageLabel . '.' . key($message);
            // todo: Fix this stub.
            $message = \Lang::get($messageLabel, $data)[1];
        }

        return $message;
    }
}
