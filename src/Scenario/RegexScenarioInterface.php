<?php

namespace Shiyan\Iterate\Scenario;

/**
 * Provides interface for regex based Iterate Scenarios.
 */
interface RegexScenarioInterface extends ScenarioInterface {

  /**
   * Gets patterns to search for in each Iterator's element.
   *
   * @return string[]
   *   Array of patterns. Order matters - if any pattern is found in an element,
   *   following patterns are not searched for in that element.
   */
  public function getPatterns(): array;

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

}
