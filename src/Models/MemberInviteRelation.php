<?php

namespace Mtsung\JoymapCore\Models;

use Fureev\Trees\Config\Base;
use Fureev\Trees\Config\LeftAttribute;
use Fureev\Trees\Config\LevelAttribute;
use Fureev\Trees\Config\RightAttribute;
use Fureev\Trees\Contracts\TreeConfigurable;
use Fureev\Trees\NestedSetTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberInviteRelation extends Model implements TreeConfigurable
{
    use NestedSetTrait;
    use HasFactory;

    protected $table = 'member_invite_relations';

    protected $guarded = ['id'];

    protected static function buildTreeConfig(): Base
    {
        $base = new Base();
        $base->setAttribute('left', app(LeftAttribute::class)->setName('left_offset'))
            ->setAttribute('right', app(RightAttribute::class)->setName('right_offset'))
            ->setAttribute('level', app(LevelAttribute::class)->setName('level'));
        return $base;
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

}
