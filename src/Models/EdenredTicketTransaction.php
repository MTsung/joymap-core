<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EdenredTicketTransaction extends Model
{
    use HasFactory;

    protected $table = "edenred_ticket_transactions";

    protected $guarded = ['id'];
}
