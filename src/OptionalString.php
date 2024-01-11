<?php

namespace Okopok\Optional;

use function is_string;

/**
 * @implements OptionalInterface<string>
 *
 * @method string get()
 * @method string|null orElse(mixed $other)
 * @method string orElseGet(callable $supplier)
 * @method string orElseThrow(callable $exceptionSupplier)
 */
class OptionalString extends AbstractOptional implements OptionalInterface
{
	/**
	 * @inheritdoc
	 */
	protected function supports(mixed $value): bool
	{
		return is_string($value);
	}
}
