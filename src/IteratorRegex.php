<?php

namespace Shiyan\Iterate;

use Shiyan\Iterate\Scenario\ScenarioInterface;
use Shiyan\Iterate\Scenario\RegexScenarioInterface;

/**
 * Performs a regular expression match on iterator elements.
 */
class IteratorRegex extends Iterate {

  /**
   * Runs the patterns search by the scenario.
   *
   * @param \Shiyan\Iterate\Scenario\RegexScenarioInterface $scenario
   *   The scenario to run the search by.
   *
   * @throws \InvalidArgumentException
   *   If the given $scenario is not of the proper interface.
   */
  public function __invoke(ScenarioInterface $scenario): void {
    if (!is_a($scenario, RegexScenarioInterface::class)) {
      throw new \InvalidArgumentException('A $scenario must implement the \Shiyan\Iterate\Scenario\RegexScenarioInterface.');
    }

    parent::__invoke($scenario);
  }

  /**
   * Runs the patterns search in the current Iterator's element.
   *
   * @param \Shiyan\Iterate\Scenario\RegexScenarioInterface $scenario
   *   The scenario to run the search by.
   *
   * @throws \Shiyan\Iterate\PregLastError
   *   If a regex execution error occurred in IteratorRegex::pregMatch().
   *
   * @see \Shiyan\Iterate\IteratorRegex::pregMatch()
   */
  protected function searchInCurrent(ScenarioInterface $scenario): void {
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
   * @return array|null
   *   The results of search, or NULL if $pattern doesn't match given $subject.
   *
   * @throws \Shiyan\Iterate\PregLastError
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
