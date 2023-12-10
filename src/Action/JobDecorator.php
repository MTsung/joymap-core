<?php

namespace Mtsung\JoymapCore\Action;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JobDecorator implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private mixed $service;
    protected array $parameters = [];

    public function __construct(string $service, ...$parameters)
    {
        $this->service = app($service);
        $this->parameters = $parameters;
    }

    public function handle()
    {
        $this->service::run(...$this->parameters);
    }
}