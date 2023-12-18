<?php

namespace Mtsung\JoymapCore\Enums;

enum OrderMailTypeEnum: string
{
    case success = 'success';
    case update = 'update';
    case cancel = 'cancel';
    case remind = 'remind';
    case commentRemind = 'comment-remind';
}
