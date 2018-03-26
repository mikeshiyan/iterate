<?php

namespace Shiyan\Iterate\Scenario;

/**
 * Provides interface for Iterate Scenarios.
 */
interface ScenarioInterface {

  /**
   * Gets the iterator instance.
   *
   * @return \Iterator
   *   An instance of object implementing Iterator.
   */
  public function getIterator(): \Iterator;

  /**
   * Executes code before iteration.
   */
  public function preRun(): void;

  /**
   * Executes code before performing a search in each element.
   */
  public function preSearch(): void;

  /**
   * Executes code for each element.
   */
  public function onEach(): void;

  /**
   * Executes code after performing a search in each element.
   */
  public function postSearch(): void;

  /**
   * Executes code after iteration.
   */
  public function postRun(): void;

}
