<?php

namespace Mtsung\JoymapCore\Helpers\Notification;


use Illuminate\Support\Collection;

interface NotificationInterface
{
    public function topic(string $topic): NotificationInterface;

    public function formatToken(Collection $tokens): array;

    public function send(array $tokens, string $title, string $body, int $badge, array $data): bool;

    public function getResponses(): Collection;
}
