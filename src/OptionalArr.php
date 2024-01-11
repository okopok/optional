<?php

namespace Okopok\Optional;

use ArrayAccess;
use Closure;

/**
 * @implements OptionalInterface<array|ArrayAccess>
 */
class OptionalArr extends AbstractOptional implements OptionalInterface
{
	/**
	 * @param array<mixed> $input
	 * @param string $key
	 * @return self
	 */
	public static function arrayKey(mixed $input, mixed $key): self
	{
		if (null !== self::dataGet($input, $key)) {
			return static::of($input);
		}
		return static::ofEmpty();
	}

	private static function dataGet(mixed $target, mixed $key, mixed $default = null): mixed
	{
		if (null === $key) {
			return $target;
		}

		$key = is_array($key) ? $key : explode('.', $key);

		foreach ($key as $i => $segment) {
			unset($key[$i]);

			if (null === $segment) {
				return $target;
			}

			if ('*' === $segment) {
				if (!is_array($target)) {
					return self::value($default);
				}

				$result = [];

				foreach ($target as $item) {
					$result[] = self::dataGet($item, $key);
				}

				if (in_array('*', $key)) {
					return self::collapse($result);
				}
				return $result;
			}

			if (self::accessible($target) && self::exists($target, $segment)) {
				$target = $target[$segment];
			} elseif (is_object($target) && isset($target->{$segment})) {
				$target = $target->{$segment};
			} else {
				return self::value($default);
			}
		}

		return $target;
	}

	private static function value(mixed $value, mixed ...$args): mixed
	{
		if ($value instanceof Closure) {
			return $value(...$args);
		}
		return $value;
	}

	/**
	 * @return array<mixed>
	 */
	private static function collapse(mixed $array): array
	{
		$results = [];

		foreach ($array as $values) {
			if (!is_array($values)) {
				continue;
			}

			$results[] = $values;
		}

		return array_merge([], ...$results);
	}

	private static function exists(mixed $array, mixed $key): bool
	{
		if ($array instanceof ArrayAccess) {
			return $array->offsetExists($key);
		}

		if (is_float($key)) {
			$key = (string)$key;
		}

		return array_key_exists($key, $array);
	}

	/**
	 * @inheritDoc
	 */
	protected function supports(mixed $value): bool
	{
		return self::accessible($value);
	}

	private static function accessible(mixed $value): bool
	{
		return is_array($value) || $value instanceof ArrayAccess;
	}
}
