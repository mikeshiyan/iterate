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
   * {@inheritdoc}
   */
  public function setIterator(\Iterator $iterator): ScenarioInterface {
    $this->iterator = $iterator;

    return $this;
  }

  /**
   * Gets the iterator instance.
   *
   * @return \Iterator
   *   An instance of object implementing Iterator.
   */
  protected function getIterator(): \Iterator {
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
