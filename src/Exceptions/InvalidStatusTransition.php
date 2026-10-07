<?php

namespace JeffersonGoncalves\MailEditor\Exceptions;

class InvalidStatusTransition extends \DomainException
{
    public static function from(string $current, string $target): self
    {
        return new self("Cannot transition from '{$current}' to '{$target}'.");
    }
}
