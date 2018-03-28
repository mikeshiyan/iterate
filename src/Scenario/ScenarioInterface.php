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
   *
   * @throws \Shiyan\Iterate\Exception\ContinueIteration
   *   If the rest of the current iteration step needs to be skipped.
   * @throws \Shiyan\Iterate\Exception\BreakIteration
   *   If the rest of the whole iteration process needs to be skipped.
   */
  public function preSearch(): void;

  /**
   * Executes code for each element.
   *
   * @throws \Shiyan\Iterate\Exception\ContinueIteration
   *   If the rest of the current iteration step needs to be skipped.
   * @throws \Shiyan\Iterate\Exception\BreakIteration
   *   If the rest of the whole iteration process needs to be skipped.
   */
  public function onEach(): void;

  /**
   * Executes code after performing a search in each element.
   *
   * @throws \Shiyan\Iterate\Exception\ContinueIteration
   *   If the rest of the current iteration step needs to be skipped.
   * @throws \Shiyan\Iterate\Exception\BreakIteration
   *   If the rest of the whole iteration process needs to be skipped.
   */
  public function postSearch(): void;

  /**
   * Executes code after iteration.
   */
  public function postRun(): void;

}
