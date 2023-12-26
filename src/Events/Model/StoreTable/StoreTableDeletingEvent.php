<?php

namespace Mtsung\JoymapCore\Events\Model\StoreTable;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Models\StoreTable;

class StoreTableDeletingEvent
{
    use Dispatchable, SerializesModels;

    public StoreTable $storeTable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(StoreTable $storeTable)
    {
        Log::info(__METHOD__ . ' start', [$storeTable]);

        $this->storeTable = $storeTable;
    }
}
