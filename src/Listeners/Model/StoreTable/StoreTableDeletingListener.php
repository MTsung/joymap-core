<?php

namespace Mtsung\JoymapCore\Listeners\Model\StoreTable;

use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Events\Model\StoreTable\StoreTableDeletingEvent;

class StoreTableDeletingListener
{
    public function handle(StoreTableDeletingEvent $event): void
    {
        Log::info(__METHOD__);

        $storeTable = $event->storeTable;

        $storeTable->refresh();

        $storeTable->combineTables()->detach();

        $storeTable->combinedByTables()->detach();
    }
}
