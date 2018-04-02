<?php

namespace Shiyan\Iterate\tests\integration\Scenario;

use Shiyan\Iterate\Scenario\ConsoleProgressBarTrait;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ClassUsingConsoleProgressBarTrait {

  use ConsoleProgressBarTrait {
    outputInProgress as progressOutputInProgress;
  }

  public $iterator;
  public $output;

  public function __construct() {
    $this->output = new BufferedOutput(OutputInterface::VERBOSITY_NORMAL, TRUE);
  }

  protected function getIterator(): \Iterator {
    return $this->iterator;
  }

  protected function getOutput(): ?OutputInterface {
    return $this->output;
  }

  public function getProgress(): ProgressBar {
    return $this->progress;
  }

  public function outputInProgress(string $line): void {
    $this->progressOutputInProgress($line);
  }

}
