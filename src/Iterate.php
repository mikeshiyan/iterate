<?php

namespace Shiyan\Iterate;

use Shiyan\Iterate\Exception\BreakIteration;
use Shiyan\Iterate\Exception\ContinueIteration;
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
      try {
        $scenario->preSearch();
        $scenario->onEach();
        $scenario->postSearch();
      }
      catch (ContinueIteration $exception) {
        continue;
      }
      catch (BreakIteration $exception) {
        break;
      }
    }

    $scenario->postRun();
  }

}
