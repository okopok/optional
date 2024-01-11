<?php

namespace Okopok\Optional;

use function is_int;

/**
 * @implements OptionalInterface<int>
 *
 * @method int get()
 * @method int|null orElse(mixed $other)
 * @method int orElseGet(callable $supplier)
 * @method int orElseThrow(callable $exceptionSupplier)
 */
class OptionalInt extends AbstractOptional implements OptionalInterface
{
	/**
	 * @inheritdoc
	 */
	protected function supports(mixed $value): bool
	{
		return is_int($value);
	}
}
