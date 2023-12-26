<?php

namespace Mtsung\JoymapCore\Events\Model\StoreFloor;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Models\StoreFloor;

class StoreFloorDeletingEvent
{
    use Dispatchable, SerializesModels;

    public StoreFloor $storeFloor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(StoreFloor $storeFloor)
    {
        Log::info(__METHOD__ . ' start', [$storeFloor]);

        $this->storeFloor = $storeFloor;
    }
}
