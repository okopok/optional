<?php

namespace Okopok\Optional;

/**
 * @implements OptionalInterface<mixed>
 */
class Optional extends AbstractOptional implements OptionalInterface
{
	protected function supports(mixed $value): bool
	{
		return true;
	}
}
