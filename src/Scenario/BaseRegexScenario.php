<?php

namespace Shiyan\Iterate\Scenario;

use Shiyan\Iterate\PregLastError;

/**
 * Defines a regex based Scenario to iterate by.
 *
 * In this scenario a regular expression match is performed on iterator
 * elements.
 *
 * Implementation classes need to implement at least the onMatch() and either
 * of getPattern()/getPatterns() methods.
 */
abstract class BaseRegexScenario extends BaseScenario implements RegexScenarioInterface {

  /**
   * {@inheritdoc}
   */
  final public function onEach(): void {
    foreach ($this->getPatterns() as $pattern) {
      $matches = self::pregMatch($pattern, (string) $this->getIterator()->current());

      if ($matches !== NULL) {
        $this->onMatch($matches, $pattern);
        return;
      }
    }

    $this->ifNotMatched();
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

  /**
   * Gets the only pattern to search for.
   *
   * This method can be implemented instead of the getPatterns() if only
   * a single pattern needs to be searched for.
   *
   * @return string
   *   Regular expression pattern.
   *
   * @throws \LogicException
   *   If neither this nor getPatterns() method is implemented.
   */
  protected function getPattern(): string {
    throw new \LogicException('You must override either of getPattern()/getPatterns() methods in the concrete scenario class.');
  }

  /**
   * {@inheritdoc}
   */
  public function getPatterns(): array {
    return [$this->getPattern()];
  }

  /**
   * {@inheritdoc}
   */
  public function ifNotMatched(): void {}

}
