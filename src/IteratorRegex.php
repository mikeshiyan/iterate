<?php

namespace Shiyan\IteratorRegex;

use Shiyan\IteratorRegex\Scenario\ScenarioInterface;

/**
 * Performs a regular expression match on iterator elements.
 */
class IteratorRegex {

  /**
   * Runs the patterns search by the scenario.
   *
   * @param \Shiyan\IteratorRegex\Scenario\ScenarioInterface $scenario
   *   The scenario to run the search by.
   *
   * @throws \Shiyan\IteratorRegex\PregLastError
   *   If a regex execution error occurred in IteratorRegex::searchInCurrent().
   *
   * @see \Shiyan\IteratorRegex\IteratorRegex::searchInCurrent()
   */
  public function __invoke(ScenarioInterface $scenario): void {
    $scenario->preRun();

    foreach ($scenario->getIterator() as $key => $current) {
      $scenario->preSearch();
      $this->searchInCurrent($scenario);
      $scenario->postSearch();
    }

    $scenario->postRun();
  }

  /**
   * Runs the patterns search in the current Iterator's element.
   *
   * @param \Shiyan\IteratorRegex\Scenario\ScenarioInterface $scenario
   *   The scenario to run the search by.
   *
   * @throws \Shiyan\IteratorRegex\PregLastError
   *   If a regex execution error occurred in IteratorRegex::pregMatch().
   *
   * @see \Shiyan\IteratorRegex\IteratorRegex::pregMatch()
   */
  public function searchInCurrent(ScenarioInterface $scenario): void {
    foreach ($scenario->getPatterns() as $pattern) {
      $matches = self::pregMatch($pattern, (string) $scenario->getIterator()->current());

      if ($matches !== NULL) {
        $scenario->onMatch($matches, $pattern);
        return;
      }
    }

    $scenario->ifNotMatched();
  }

  /**
   * Searches $subject for a match to the regular expression given in $pattern.
   *
   * @param string $pattern
   *   The pattern to search for.
   * @param string $subject
   *   The input string.
   *
   * @return array|NULL
   *   The results of search, or NULL if $pattern doesn't match given $subject.
   *
   * @throws \Shiyan\IteratorRegex\PregLastError
   *   If a regex execution error occurred.
   *
   * @see preg_match()
   */
  public static function pregMatch(string $pattern, string $subject): ?array {
    $result = @preg_match($pattern, $subject, $matches);

    if ($result === FALSE) {
      throw new PregLastError();
    }

    return $result !== 0 ? $matches : NULL;
  }

}
