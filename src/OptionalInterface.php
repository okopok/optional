<?php

namespace Okopok\Optional;

use Exception;
use InvalidArgumentException;
use LogicException;

/**
 * Php implementation of Java-9 Optional, with 'generics' support
 *
 * A container object which may or may not contain a non-null value.
 *
 * If a value is present, isPresent() returns true and get() returns the value.
 *
 * Additional methods that depend on the presence or absence of a contained value are provided,
 * such as orElse() (returns a default value if no value is present) and ifPresent()
 * (performs an action if a value is present).
 *
 * @see http://download.java.net/java/jdk9/docs/api/java/util/Optional.html
 */
interface OptionalInterface
{
    /**
     * Returns an Optional describing the given non-null value.
     *
     * @param mixed $value the value to be present, which must be non-null
     * @return static an Optional with the value present
     * @throws InvalidArgumentException if value is null or unsupported
     */
    public static function of(mixed $value): static;

    /**
     * Returns an empty Optional instance. No value is present for this Optional.
     * Replaces Java Optional::empty() ('empty' is a reserved word in php)
     *
     * @return static an empty Optional
     */
    public static function ofEmpty(): static;

    /**
     * Returns an Optional describing the given value, if non-null, otherwise returns an empty Optional.
     *
     * @param mixed|null $value the possibly-null value to describe
     * @return static an Optional with a present value if the specified value
     * @throws InvalidArgumentException
     */
    public static function ofNullable(mixed $value): static;

    /**
     * If a value is present, returns an Optional describing (as if by ofNullable(T))
     * the result of applying the given mapping function to the value, otherwise returns an empty Optional.
     *
     * If the mapping function returns a null result then this method returns an empty Optional
     *
     * @param callable $callback the mapping function to apply to a value, if present
     *
     * if a value is present, otherwise an empty Optional
     * @return static an Optional describing the result of applying a mapping function to the value of this Optional,
     * @throws InvalidArgumentException
     */
    public function map(callable $callback): static;

    /**
     * @param OptionalInterface $optional
     * @return static
     */
    public function or(self $optional): static;

    /**
     * If a value is present, returns the value, otherwise throws LogicException.
     *
     * @return mixed the non-null value held by this Optional
     * @throws LogicException if there is no value present
     */
    public function get(): mixed;

    /**
     * If a value is present, returns true, otherwise false.
     *
     * @return bool true if a value is present, otherwise false
     */
    public function isPresent(): bool;

    /**
     * If a value is present, performs the given action with the value, otherwise does nothing.
     *
     * @param callable $action the action to be performed, if a value is present
     * @return static API change compared to Java Optional for allowing chain
     */
    public function ifPresent(callable $action): static;

    /**
     * If a value is not present, performs the given action with the value, otherwise does nothing.
     *
     * @param callable $emptyAction the action to be performed, if a value is not present
     * @return void this is deliberately "void" to disallow ifAbsent()->orElse()
     */
    public function ifAbsent(callable $emptyAction): void;

    /**
     * If a value is present, performs the given action with the value,
     * otherwise performs the given empty-based action.
     *
     * @param callable $action the action to be performed, if a value is present
     * @param callable $emptyAction the empty-based action to be performed, if no value is present
     * @return void
     */
    public function ifPresentOrElse(callable $action, callable $emptyAction): void;

    /**
     * If a value is present, returns the result of applying the given Optional-bearing mapping
     * function to the value, otherwise returns an empty Optional.
     *
     * This method is similar to map(Function), but the mapping function is one whose result
     * is already an Optional, and if invoked, flatMap does not wrap it within an additional
     * Optional.
     *
     * @param callable $mapper the mapping function to apply to a value, if present
     * @return static the result of applying an Optional-bearing mapping function to the value of this Optional,
     * if a value is present, otherwise an empty Optional
     * @throws InvalidArgumentException if the mapping function is null or returns a null result
     */
    public function flatMap(callable $mapper): self;

    /**
     * If a value is present, and the value matches the given predicate,
     * returns an Optional describing the value, otherwise returns an empty Optional.
     *
     * @param callable $predicate the predicate to apply to a value, if present
     * @return static an Optional describing the value of this Optional, if a value
     * is present and the value matches the given predicate, otherwise an empty Optional
     * @throws InvalidArgumentException if the predicate is null
     */
    public function filter(callable $predicate): static;

    /**
     * If a value is present, returns the value, otherwise returns other.
     *
     * @param mixed|null $other the value to be returned, if no value is present. May be null.
     * @return mixed|null the value, if present, otherwise other
     * @throws InvalidArgumentException
     */
    public function orElse(mixed $other): mixed;

    /**
     * If a value is present, returns the value, otherwise returns the result produced
     * by the supplying function.
     *
     * @param callable $supplier the supplying function that produces a value to be returned
     * @return mixed the value, if present, otherwise the result produced by the supplying function, cannnot be null
     * @throws InvalidArgumentException if no value is present and the supplying function is null
     */
    public function orElseGet(callable $supplier): mixed;

    /**
     * If a value is present, returns the value, otherwise throws an exception produced by
     * the exception supplying function.
     *
     * @param callable $exceptionSupplier the supplying function that produces an exception to be thrown
     * @return mixed the value, if present
     * @throws Exception if there is no value present
     * @throws InvalidArgumentException if no value is present and the exception supplying function is null
     */
    public function orElseThrow(callable $exceptionSupplier): mixed;

    /**
     * If a value is present, returns an Optional describing the value, otherwise returns an Optional
     * produced by the supplying function.
     *
     * Replaces Java Optional::or() ('or' is a reserved word in php)
     *
     * @param callable $supplier the supplying function that produces an Optional to be returned
     * @return static returns an Optional describing the value of this Optional, if a value is present,
     * otherwise an Optional produced by the supplying function.
     * @throws InvalidArgumentException is the value of the optional from the supplier is not supported
     */
    public function orElseOptional(callable $supplier): static;

    /**
     * Indicates whether some other object is "equal to" this Optional.
     *
     * The other object is considered equal if:
     *   - it is also an Optional and;
     *   - both instances have no value present or;
     *   - the present values are "equal to" each other via equals()
     *
     * @param mixed $obj an object to be tested for equality
     * @return bool true if the other object is "equal to" this object otherwise false
     */
    public function equals(mixed $obj): bool;

    public function apply(callable $apply): void;
}
