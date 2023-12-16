<?php

namespace Mtsung\JoymapCore\Enums;

enum PushNotificationToTypeEnum: string
{
    case members = 'members';
    case member = 'member';
    case stores = 'stores';
    case store = 'store';
    case tokens = 'tokens';
    case token = 'token';
}
