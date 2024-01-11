<?php

namespace Okopok\Optional;

use function is_int;

/**
 * @method int get()
 * @method int|null orElse(mixed $other)
 * @method int orElseGet(callable $supplier)
 * @method int orElseThrow(callable $exceptionSupplier)
 */
class OptionalInt extends AbstractOptional
{
    /**
     * @inheritdoc
     */
	protected function supports(mixed $value): bool
    {
        return is_int($value);
    }
}
