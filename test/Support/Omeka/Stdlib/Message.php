<?php
declare(strict_types=1);

namespace Omeka\Stdlib;

class Message
{
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function __toString(): string
    {
        return $this->message;
    }
}
