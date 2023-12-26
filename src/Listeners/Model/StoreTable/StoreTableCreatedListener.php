<?php

namespace Mtsung\JoymapCore\Listeners\Model\StoreTable;

use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Events\Model\StoreTable\StoreTableCreatedEvent;

class StoreTableCreatedListener
{
    public function handle(StoreTableCreatedEvent $event): void
    {
        Log::info(__METHOD__);

        $storeTable = $event->storeTable;

        $storeTable->refresh();

        $storeTableCombinations = $storeTable->storeFloor->storeTableCombinations()->create([
            'min' => $storeTable->min,
            'max' => $storeTable->max,
            'can_book_online' => $storeTable->can_book_online,
            'combination' => [$storeTable->id],
            'combination_name' => $storeTable->name ?? null,
            'store_id' => $storeTable->storeFloor->store_id,
            'people_combination' => range($storeTable->min, $storeTable->max),
        ]);

        $storeTableCombinations->update([
            'relation_ids' => [$storeTableCombinations->id],
        ]);
    }
}
