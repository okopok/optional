<?php

namespace Okopok\Optional;

use function is_bool;

/**
 * @implements OptionalInterface<bool>
 *
 * @method bool get()
 * @method bool|null orElse(mixed $other)
 * @method bool orElseGet(callable $supplier)
 * @method bool orElseThrow(callable $exceptionSupplier)
 */
class OptionalBool extends AbstractOptional implements OptionalInterface
{
	/**
	 * @inheritdoc
	 */
	protected function supports(mixed $value): bool
	{
		return is_bool($value);
	}
}
