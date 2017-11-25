<?php

namespace Shiyan\IteratorRegex\Scenario;

/**
 * Defines a base Scenario to run IteratorRegex by.
 *
 * Implementation classes need to implement at least the onMatch() and either
 * of getPattern()/getPatterns() methods.
 */
abstract class BaseScenario implements ScenarioInterface {

  /**
   * Iterator to perform a regular expression match in.
   *
   * @var \Iterator
   */
  protected $iterator;

  /**
   * Constructs a base Scenario object.
   *
   * @param \Iterator $iterator
   *   Iterator to perform a regular expression match in.
   */
  public function __construct(\Iterator $iterator) {
    $this->iterator = $iterator;
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    return $this->iterator;
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
  public function preRun(): void {}

  /**
   * {@inheritdoc}
   */
  public function preSearch(): void {}

  /**
   * {@inheritdoc}
   */
  public function ifNotMatched(): void {}

  /**
   * {@inheritdoc}
   */
  public function postSearch(): void {}

  /**
   * {@inheritdoc}
   */
  public function postRun(): void {}

}
