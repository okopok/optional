<?php

namespace Okopok\Optional;

use InvalidArgumentException;
use LogicException;
use function array_key_exists;
use function sprintf;

/**
 * @template T of OptionalInterface
 */
abstract class AbstractOptional implements OptionalInterface
{
    /**
     * Static per concrete class cache for optional empty instances, for GC optimization
     *
     * @var static[] ["$class1" => $emptyInstance1, "$class2" => $emptyInstance2, ...]
     */
    private static array $emptyCache = [];

    /**
     * The value, or null is no value is present
     *
     * @var mixed|null
     */
    private mixed $value;

    /**
     * Private Constructor
     */
    final private function __construct()
    {
    }

    public function or(OptionalInterface $optional): static
    {
        if ($this->isPresent()) {
            return $this;
        }

        return static::ofEmpty();
    }


    public function isPresent(): bool
    {
        return null !== $this->value;
    }

    public static function ofEmpty(): static
    {
        if (!array_key_exists(static::class, self::$emptyCache)) {
            self::$emptyCache[static::class] = new static();
        }

        return self::$emptyCache[static::class];
    }

    public function ifPresent(callable $action): static
    {
        if (null !== $this->value) {
            $action($this->value);
        }

        return $this;
    }

    public function ifAbsent(callable $emptyAction): void
    {
        if (null === $this->value) {
            $emptyAction();
        }
    }

    public function ifPresentOrElse(callable $action, callable $emptyAction): void
    {
        if (null !== $this->value) {
            $action($this->value);
        } else {
            $emptyAction();
        }
    }

    public function map(callable $callback): static
    {
        if (null === $this->value) {
            return self::ofEmpty();
        }
        return self::ofNullable($callback($this->value));
    }

    public static function ofNullable(mixed $value): static
    {
        if (null !== $value) {
            return self::of($value);
        }
        return self::ofEmpty();
    }


    public static function of(mixed $value): static
    {
        if (null === $value) {
            throw new InvalidArgumentException(
                sprintf('Value for %s cannot be null, use Optional::ofNullable instead', static::class)
            );
        }

        $self = new static;
        $self->value = $self->validate($value);

        return $self;
    }

    /**
     * Validates the value only if not null
     *
     * @param mixed|null $value
     * @return mixed|null the value, if valid or null
     * @throws InvalidArgumentException if the value is not null and not supported
     */
    private function validate(mixed $value): mixed
    {
        if (null !== $value && !$this->supports($value)) {
            throw new InvalidArgumentException(sprintf('The value for %s is unsupported', static::class));
        }

        return $value;
    }

    /**
     * Mime the 'generics' support for this optional
     *
     * @param mixed $value cannot be null
     * @return bool
     */
    abstract protected function supports(mixed $value): bool;

    public function flatMap(callable $mapper): static
    {
        if (null === $this->value) {
            return static::ofEmpty();
        }

        $optional = $mapper($this->value);

        if (!$optional instanceof static) {
            throw new InvalidArgumentException(sprintf('Supplier must return a %s instance', OptionalInterface::class));
        }

        return $optional;
    }


    public function filter(callable $predicate): static
    {
        if (null === $this->value || $predicate($this->value)) {
            return $this;
        }
        return self::ofEmpty();
    }


    public function orElse(mixed $other): mixed
    {
        if (null !== $this->value) {
            return $this->value;
        }
        return $this->validate($other);
    }


    public function orElseGet(callable $supplier): mixed
    {
        if (null !== $this->value) {
            return $this->value;
        }

        $value = $supplier();

        if (null === $value) {
            throw new InvalidArgumentException(sprintf('The value for %s::orElseGet cannot be null', static::class));
        }

        return $this->validate($value);
    }


    public function orElseThrow(callable $exceptionSupplier): mixed
    {
        if (null === $this->value) {
            throw $exceptionSupplier();
        }

        return $this->value;
    }


    public function orElseOptional(callable $supplier): static
    {
        if (null !== $this->value) {
            return $this;
        }

        $optional = $supplier();

        if (!$optional instanceof static) {
            throw new InvalidArgumentException(sprintf('Supplier must return a %s instance', static::class));
        }

        return $optional;
    }


    public function equals(mixed $obj): bool
    {
        return $obj === $this || ($obj instanceof static && $obj->value === $this->value);
    }

    /**
     * Returns a non-empty string representation of this Optional suitable for debugging.
     * The exact presentation format is unspecified and may vary between implementations
     * and versions.
     *
     * @return string the string representation of this instance
     */
    public function __toString(): string
    {
        $value = $this->value;

        if (is_object($value)) {
            $value = json_decode(json_encode($value) ?: '', true);
        }

        if (is_array($value)) {
            $value = sprintf('[%s]', implode(', ', $value));
        }

        if (null != $value) {
            return sprintf('Optional[%s]', $value);
        }
        return 'Optional[empty]';
    }

    public function apply(callable $apply): void
    {
        if (!$this->isPresent()) {
            return;
        }

        $apply($this->value);
    }


    public function get(): mixed
    {
        if (null === $this->value) {
            throw new LogicException(sprintf('No value present for %s, use ::orElse instead', static::class));
        }

        return $this->value;
    }
}
