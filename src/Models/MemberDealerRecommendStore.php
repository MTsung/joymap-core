<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberDealerRecommendStore extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //未讀取
    public const IS_READ_OFF = 0;
    //已讀取
    public const IS_READ_ON = 1;

    //未綁定店家
    public const IS_BIND_OFF = 0;
    //已綁定店家
    public const IS_BIND_ON = 1;

    public function memberDealer()
    {
        return $this->belongsTo(MemberDealer::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'store_city_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'store_district_id', 'id');
    }
}
