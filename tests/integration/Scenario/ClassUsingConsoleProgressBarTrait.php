<?php

namespace Shiyan\Iterate\tests\integration\Scenario;

use Shiyan\Iterate\Scenario\BaseScenario;
use Shiyan\Iterate\Scenario\ConsoleProgressBarTrait;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ClassUsingConsoleProgressBarTrait extends BaseScenario {

  use ConsoleProgressBarTrait {
    getOutput as progressGetOutput;
    outputInProgress as progressOutputInProgress;
  }

  public function __construct() {
    $this->setOutput(new BufferedOutput(OutputInterface::VERBOSITY_NORMAL, TRUE));
  }

  public function getProgress(): ProgressBar {
    return $this->progress;
  }

  public function getOutput(): ?OutputInterface {
    return $this->progressGetOutput();
  }

  public function outputInProgress(string $line): void {
    $this->progressOutputInProgress($line);
  }

  public function onEach(): void {}

}
