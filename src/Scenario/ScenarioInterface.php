<?php

namespace Shiyan\IteratorRegex\Scenario;

/**
 * Provides interface for IteratorRegex Scenarios.
 */
interface ScenarioInterface {

  /**
   * Gets the iterator to perform a regular expression match in.
   *
   * @return \Iterator
   *   An instance of object implementing Iterator.
   */
  public function getIterator(): \Iterator;

  /**
   * Gets patterns to search for in each Iterator's element.
   *
   * @return string[]
   *   Array of patterns. Order matters - if any pattern is found in an element,
   *   following patterns are not searched for in that element.
   */
  public function getPatterns(): array;

  /**
   * Executes code before iteration.
   */
  public function preRun(): void;

  /**
   * Executes code before performing a search in each element.
   */
  public function preSearch(): void;

  /**
   * Executes code if $pattern matches an element.
   *
   * @param array $matches
   *   Array with the results of search. $matches[0] contains the text that
   *   matches the full pattern, $matches[1] has the text that matches the first
   *   captured parenthesized subpattern, and so on.
   * @param string $pattern
   *   The pattern that matches an element.
   *
   * @see preg_match()
   */
  public function onMatch(array $matches, string $pattern): void;

  /**
   * Executes code if no patterns match an element.
   */
  public function ifNotMatched(): void;

  /**
   * Executes code regardless of search success in each element.
   */
  public function postSearch(): void;

  /**
   * Executes code after iteration.
   */
  public function postRun(): void;

}
