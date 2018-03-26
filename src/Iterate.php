<?php

namespace Shiyan\Iterate;

use Shiyan\Iterate\Scenario\ScenarioInterface;

/**
 * Iterates by a scenario.
 */
class Iterate {

  /**
   * Runs the iteration by the scenario.
   *
   * @param \Shiyan\Iterate\Scenario\ScenarioInterface $scenario
   *   The scenario to iterate by.
   */
  public function __invoke(ScenarioInterface $scenario): void {
    $scenario->preRun();

    foreach ($scenario->getIterator() as $key => $current) {
      $scenario->preSearch();
      $scenario->onEach();
      $scenario->postSearch();
    }

    $scenario->postRun();
  }

}
