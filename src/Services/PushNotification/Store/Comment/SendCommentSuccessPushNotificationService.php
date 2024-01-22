<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Store\Comment;

use Exception;
use Illuminate\Support\Str;
use Mtsung\JoymapCore\Enums\PushNotificationToTypeEnum;
use Mtsung\JoymapCore\Events\Comment\CommentSuccessEvent;
use Mtsung\JoymapCore\Models\Comment;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Services\PushNotification\PushNotificationAbstract;


/**
 * @method static dispatch(Store $to, Comment $comment)
 * @method static bool run(Store $to, Comment $comment)
 */
class SendCommentSuccessPushNotificationService extends PushNotificationAbstract
{
    public function toType(): PushNotificationToTypeEnum
    {
        return PushNotificationToTypeEnum::store;
    }

    public function title(): string
    {
        return __('joymap::notification.comment.title');
    }

    public function body(): string
    {
        $comment = $this->arguments;

        return __('joymap::notification.comment.body', [
            'body' => Str::limit($comment->body, 30),
        ]);
    }

    public function action(): string
    {
        return 'comment.list';
    }

    public function data(): array
    {
        $comment = $this->arguments;

        return [
            'comment_id' => $comment->id ?? 0,
        ];
    }

    /**
     * @throws Exception
     */
    public function asListener(CommentSuccessEvent $event): bool
    {
        return self::run($event->comment->store, $event->comment);
    }
}
