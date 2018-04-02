<?php

namespace Shiyan\Iterate\Scenario;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Defines a basic ProgressBar feature for Iterate Scenarios.
 */
trait ConsoleProgressBarTrait {

  /**
   * Progress bar instance.
   *
   * @var \Symfony\Component\Console\Helper\ProgressBar
   */
  protected $progress;

  /**
   * Gets the iterator to get its size and current position.
   *
   * @return \Iterator
   *   An instance of object implementing Iterator.
   *
   * @see \Shiyan\Iterate\Scenario\BaseScenario::getIterator()
   */
  abstract protected function getIterator(): \Iterator;

  /**
   * Gets an OutputInterface instance.
   *
   * @return \Symfony\Component\Console\Output\OutputInterface|null
   *   An OutputInterface instance or NULL. If NULL is returned, then the
   *   ProgressBar won't be instantiated.
   */
  abstract protected function getOutput(): ?OutputInterface;

  /**
   * Starts a new progress bar output in the pre-run phase.
   *
   * @see \Shiyan\Iterate\Scenario\ScenarioInterface::preRun()
   */
  public function preRun(): void {
    if ($output = $this->getOutput()) {
      $max = 0;
      $iterator = $this->getIterator();

      if ($iterator instanceof \SplFileObject) {
        $max = $iterator->getSize();
      }
      elseif ($iterator instanceof \Countable) {
        $max = count($iterator);
      }

      $this->progress = new ProgressBar($output, $max);
      if ($output->isDecorated()) {
        // If output is not decorated, the redraw frequency would automatically
        // be set to $max/10 by default.
        $this->progress->setRedrawFrequency($max / 100);
      }
      $this->progress->start();
    }
  }

  /**
   * Updates the current progress in the post-search phase.
   *
   * @see \Shiyan\Iterate\Scenario\ScenarioInterface::postSearch()
   */
  public function postSearch(): void {
    if ($this->progress) {
      $iterator = $this->getIterator();

      if ($iterator instanceof \SplFileObject) {
        // Get current byte offset, after the line was read.
        $this->progress->setProgress($iterator->ftell());
      }
      elseif (is_int($key = $iterator->key())) {
        // Add one to the current index, because we're in the post-search phase.
        $this->progress->setProgress($key + 1);
      }
      else {
        // If iterator's key is not integer, just advance the progress with 1.
        $this->progress->advance();
      }
    }
  }

  /**
   * Finishes the progress output in the post-run phase.
   *
   * @see \Shiyan\Iterate\Scenario\ScenarioInterface::postRun()
   */
  public function postRun(): void {
    if ($this->progress) {
      $this->progress->finish();
      // Progress bar does not print a new-line at the end.
      $this->getOutput()->writeln('');
    }
  }

  /**
   * Outputs a line of text while a progress bar is running.
   *
   * @param string $line
   *   Text to output.
   */
  protected function outputInProgress(string $line): void {
    if ($this->progress) {
      $this->progress->clear();
      $this->getOutput()->writeln($line);
      $this->progress->display();
    }
  }

}
