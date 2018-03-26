<?php

namespace Shiyan\Iterate\Scenario;

/**
 * Defines a base Scenario to run IteratorRegex by.
 *
 * Implementation classes need to implement at least the onMatch() and either
 * of getPattern()/getPatterns() methods.
 */
abstract class BaseRegexScenario extends BaseScenario implements RegexScenarioInterface {

  /**
   * {@inheritdoc}
   */
  public function onEach(): void {}

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
