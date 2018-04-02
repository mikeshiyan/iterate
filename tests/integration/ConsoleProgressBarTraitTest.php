<?php

use PHPUnit\Framework\TestCase;
use Shiyan\Iterate\Scenario\ConsoleProgressBarTrait;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class TestClassUsingConsoleProgressBarTrait {

  use ConsoleProgressBarTrait {
    outputInProgress as progressOutputInProgress;
  }

  public $iterator;
  public $output;

  public function __construct() {
    $this->output = new BufferedOutput(OutputInterface::VERBOSITY_NORMAL, TRUE);
  }

  protected function getIterator(): Iterator {
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

class ConsoleProgressBarTraitTest extends TestCase {

  public function testProgress_WithEmptyIterator() {
    $scenario = new TestClassUsingConsoleProgressBarTrait();
    $scenario->iterator = new EmptyIterator();

    $scenario->preRun();
    $this->assertSame(0, $scenario->getProgress()->getMaxSteps());
    $this->assertSame(0, $scenario->getProgress()->getProgress());

    $scenario->outputInProgress('Abc.');
    $this->assertContains("Abc.\n", $scenario->output->fetch());
  }

  public function testProgress_WithCountable() {
    $scenario = new TestClassUsingConsoleProgressBarTrait();
    $scenario->iterator = new ArrayIterator(['a', 'b', 'c']);

    // Set current to other than first, but expect the progress to show current
    // step = 0, because it was not advanced yet.
    $scenario->iterator->seek(1);
    $scenario->preRun();
    $this->assertSame(3, $scenario->getProgress()->getMaxSteps());
    $this->assertSame(0, $scenario->getProgress()->getProgress());

    $scenario->postSearch();
    $this->assertSame(2, $scenario->getProgress()->getProgress());

    $scenario->postRun();
    $this->assertSame(3, $scenario->getProgress()->getProgress());
  }

  public function testProgress_WithSplFileObject() {
    $scenario = new TestClassUsingConsoleProgressBarTrait();
    $scenario->iterator = new SplFileObject(__FILE__, 'rb');
    $filesize = filesize(__FILE__);

    $scenario->preRun();
    $this->assertSame($filesize, $scenario->getProgress()->getMaxSteps());
    $this->assertSame(0, $scenario->getProgress()->getProgress());

    // Read the first line from the file, so current position will be =
    // strlen("<?php\n") = 6.
    $scenario->iterator->current();
    $scenario->postSearch();
    $this->assertSame(6, $scenario->getProgress()->getProgress());

    $scenario->postRun();
    $this->assertSame($filesize, $scenario->getProgress()->getProgress());
  }

}
