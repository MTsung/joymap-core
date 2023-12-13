<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberPhoneValidate extends Model
{
    use HasFactory;

    protected $table = 'member_phone_validates';

    public $timestamps = ["created_at"];

    const UPDATED_AT = null;

    protected $guarded = ['id'];
}
