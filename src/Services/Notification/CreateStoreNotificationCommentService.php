<?php

namespace Mtsung\JoymapCore\Services\Notification;

use Exception;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Events\Comment\CommentSuccessEvent;
use Mtsung\JoymapCore\Models\Comment;
use Mtsung\JoymapCore\Models\StoreNotification;
use Mtsung\JoymapCore\Repositories\Store\StoreNotificationRepository;

/**
 * @method static void run(Comment $comment)
 */
class CreateStoreNotificationCommentService
{
    use AsObject;

    public function __construct(
        private StoreNotificationRepository $storeNotificationRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function handle(Comment $comment): void
    {
        $data = [
            'store_id' => $comment->store_id,
            'title' => __('joymap::notification.comment.title'),
            'status' => StoreNotification::STATUS_COMMENT,
            'order_id' => $comment->order_id,
            'comment_id' => $comment->id,
            'pay_log_id' => $comment->pay_log_id,
        ];

        $this->storeNotificationRepository->create($data);
    }

    /**
     * @throws Exception
     */
    public function asListener(CommentSuccessEvent $event): void
    {
        self::run($event->comment);
    }
}
