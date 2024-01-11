<?php

namespace Okopok\Optional;

class Optional extends AbstractOptional
{
    protected function supports(mixed $value): bool
    {
        return true;
    }
}
