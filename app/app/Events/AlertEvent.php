<?php

namespace App\Events;

use Log;
use App\Models\Camera;
use App\Models\Alert;

class AlertEvent extends Event
{

    public $camera;

    public $alert;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Camera $camera, Alert $alert)
    {
        $this->camera = $camera;

        $this->alert = $alert;
    }
}
