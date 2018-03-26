<?php

namespace Shiyan\Iterate\Scenario;

/**
 * Defines a base Scenario to iterate by.
 *
 * Implementation classes need to implement at least the onEach() method.
 */
abstract class BaseScenario implements ScenarioInterface {

  /**
   * Iterator instance.
   *
   * @var \Iterator
   */
  protected $iterator;

  /**
   * Constructs a base Scenario object.
   *
   * @param \Iterator $iterator
   *   Iterator instance.
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
  public function postSearch(): void {}

  /**
   * {@inheritdoc}
   */
  public function postRun(): void {}

}
