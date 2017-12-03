<?php

namespace Shiyan\IteratorRegex\Scenario;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Defines a basic ProgressBar feature for IteratorRegex Scenarios.
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
   * @see \Shiyan\IteratorRegex\Scenario\ScenarioInterface::getIterator()
   */
  abstract public function getIterator(): \Iterator;

  /**
   * Gets an OutputInterface instance.
   *
   * @return \Symfony\Component\Console\Output\OutputInterface
   *   An OutputInterface instance.
   */
  abstract protected function getOutput(): OutputInterface;

  /**
   * Starts a new progress bar output in the pre-run phase.
   *
   * @throws \RuntimeException
   *   If the symfony/console component is not installed.
   *
   * @see \Shiyan\IteratorRegex\Scenario\ScenarioInterface::preRun()
   */
  public function preRun(): void {
    if (!class_exists(ProgressBar::class)) {
      // @codeCoverageIgnoreStart
      throw new \RuntimeException('The progress bar feature is only enabled in conjunction with the symfony/console component.');
      // @codeCoverageIgnoreEnd
    }

    $max = 0;
    $iterator = $this->getIterator();

    if ($iterator instanceof \SplFileObject) {
      $max = $iterator->getSize();
    }
    elseif ($iterator instanceof \Countable) {
      $max = count($iterator);
    }

    $this->progress = new ProgressBar($this->getOutput(), $max);
    if ($this->getOutput()->isDecorated()) {
      // If output is not decorated, the redraw frequency would automatically be
      // set to $max/10 by default.
      $this->progress->setRedrawFrequency($max / 100);
    }
    $this->progress->start();
  }

  /**
   * Updates the current progress in the post-search phase.
   *
   * @see \Shiyan\IteratorRegex\Scenario\ScenarioInterface::postSearch()
   */
  public function postSearch(): void {
    if ($this->progress) {
      $iterator = $this->getIterator();

      if ($iterator instanceof \SplFileObject) {
        // Get current byte offset, after the line was read.
        $step = $iterator->ftell();
      }
      else {
        // Add one to the current index, because we're in the post-search phase.
        $step = $iterator->key() + 1;
      }

      $this->progress->setProgress($step);
    }
  }

  /**
   * Finishes the progress output in the post-run phase.
   *
   * @see \Shiyan\IteratorRegex\Scenario\ScenarioInterface::postRun()
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
