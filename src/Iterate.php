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
      $this->searchInCurrent($scenario);
      $scenario->postSearch();
    }

    $scenario->postRun();
  }

  /**
   * Runs the search in the current Iterator's element.
   *
   * @param \Shiyan\Iterate\Scenario\ScenarioInterface $scenario
   *   The scenario to iterate by.
   */
  protected function searchInCurrent(ScenarioInterface $scenario): void {
    $scenario->onEach();
  }

}
