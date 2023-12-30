<?php

namespace Mtsung\JoymapCore\Events\Comment;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
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
        $this->comment= $comment;
    }
}
