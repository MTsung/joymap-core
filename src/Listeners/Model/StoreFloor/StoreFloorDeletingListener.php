<?php

namespace Mtsung\JoymapCore\Listeners\Model\StoreFloor;

use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Events\Model\StoreFloor\StoreFloorDeletingEvent;
use Mtsung\JoymapCore\Models\StoreTable;

class StoreFloorDeletingListener
{
    public function handle(StoreFloorDeletingEvent $event): void
    {
        Log::info(__METHOD__);

        $storeFloor = $event->storeFloor;

        $storeFloor->storeTables->each(fn(StoreTable $table) => $table->delete());

        $storeFloor->storeTableCombinations()->delete();
    }
}
