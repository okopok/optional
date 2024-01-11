<?php

namespace Okopok\Optional\Tests;

use ArrayObject;
use Okopok\Optional\OptionalArr;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class OptionalArrTest extends TestCase
{
	/**
	 * @return array<mixed>
	 */
	public static function positiveProvider(): array
	{
		return [
			[['foo' => 'bar'], 'foo'],
			[['foo' => ['bar' => 'baz']], 'foo.bar'],
			[new ArrayObject(['foo' => 'bar']), 'foo'],
		];
	}

	/**
	 * @return array<mixed>
	 */
	public static function negativeProvider(): array
	{
		return [
			[['foo' => 'bar'], 'bar'],
			[['foo' => ['bar' => 'baz']], 'foo.baz'],
			[new ArrayObject(['foo' => 'bar']), 'bar'],
		];
	}

	#[Test, DataProvider('positiveProvider')]
	public function positive(mixed $input, string $key): void
	{
		$this->assertTrue(OptionalArr::arrayKey($input, $key)->isPresent());
	}

	#[Test, DataProvider('negativeProvider')]
	public function negative(mixed $input, string $key): void
	{
		$this->assertFalse(OptionalArr::arrayKey($input, $key)->isPresent());
	}
}
