<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $guarded = [];

    const TYPE_DEALER = 0;  //經銷商劵
}
