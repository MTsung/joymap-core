<?php

namespace Mtsung\JoymapCore\Events\Comment;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Models\Comment;

class CommentSuccessEvent
{
    use Dispatchable, SerializesModels;

    public Comment $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        Log::info(__METHOD__ . ' start', [$comment->id]);

        $this->comment = $comment;
    }
}
