<?php

namespace Okopok\Optional;

use function is_float;

/**
 * @implements OptionalInterface<float>
 *
 * @method float get()
 * @method float|null orElse(mixed $other)
 * @method float orElseGet(callable $supplier)
 * @method float orElseThrow(callable $exceptionSupplier)
 */
class OptionalFloat extends AbstractOptional implements OptionalInterface
{
	/**
	 * @inheritdoc
	 */
	protected function supports(mixed $value): bool
	{
		return is_float($value);
	}
}
