<?php

namespace Mtsung\JoymapCore\Enums;

enum OrderMailTypeEnum: string
{
    case success = 'success';
    case serviceSuccess = 'service-success';
    case update = 'update';
    case cancel = 'cancel';
    case remind = 'remind';
    case serviceRemind = 'service-remind';
    case commentRemind = 'comment-remind';
}
