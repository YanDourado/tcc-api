<?php

namespace App\Listeners;

use App\Classes\Notification;
use App\Events\AlertEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AlertListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AlertEvent  $event
     * @return void
     */
    public function handle(AlertEvent $event)
    {
        //

        $camera = $event->camera;

        $alert = $event->alert;

        if(!$camera->status) return;

        $user = $camera->User;

        $cameraInfo = $camera->CameraInfo;

        if(!$user || !$cameraInfo) return;

        $notification = new Notification();

        $title = "Alerta de movimento!";

        $body = "Um alerta de movimento foi gerado na câmera: $cameraInfo->name";

        $notification->send($title, $body);
    }
}
