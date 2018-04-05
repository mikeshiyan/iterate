<?php

namespace Shiyan\Iterate\tests\integration\Scenario;

use PHPUnit\Framework\TestCase;

class ConsoleProgressBarTraitTest extends TestCase {

  public function testProgress_WithEmptyIterator() {
    $scenario = new ClassUsingConsoleProgressBarTrait();
    $scenario->setIterator(new \EmptyIterator());

    $scenario->preRun();
    $this->assertSame(0, $scenario->getProgress()->getMaxSteps());
    $this->assertSame(0, $scenario->getProgress()->getProgress());

    $scenario->outputInProgress('Abc.');
    $this->assertContains("Abc.\n", $scenario->getOutput()->fetch());
  }

  public function testProgress_WithCountable() {
    $scenario = new ClassUsingConsoleProgressBarTrait();
    $iterator = new \ArrayIterator(['a', 'b', 'c']);
    $scenario->setIterator($iterator);

    // Set current to other than first, but expect the progress to show current
    // step = 0, because it was not advanced yet.
    $iterator->seek(1);
    $scenario->preRun();
    $this->assertSame(3, $scenario->getProgress()->getMaxSteps());
    $this->assertSame(0, $scenario->getProgress()->getProgress());

    $scenario->postSearch();
    $this->assertSame(2, $scenario->getProgress()->getProgress());

    $scenario->postRun();
    $this->assertSame(3, $scenario->getProgress()->getProgress());
  }

  public function testProgress_WithSplFileObject() {
    $scenario = new ClassUsingConsoleProgressBarTrait();
    $iterator = new \SplFileObject(__FILE__, 'rb');
    $scenario->setIterator($iterator);
    $filesize = filesize(__FILE__);

    $scenario->preRun();
    $this->assertSame($filesize, $scenario->getProgress()->getMaxSteps());
    $this->assertSame(0, $scenario->getProgress()->getProgress());

    // Read the first line from the file, so current position will be =
    // strlen("<?php\n") = 6.
    $iterator->current();
    $scenario->postSearch();
    $this->assertSame(6, $scenario->getProgress()->getProgress());

    $scenario->postRun();
    $this->assertSame($filesize, $scenario->getProgress()->getProgress());
  }

  public function testProgress_WithNonIntIteratorKeys() {
    $scenario = new ClassUsingConsoleProgressBarTrait();
    $iterator = new class(['a', 'b', 'c']) extends \ArrayIterator {
      public function key() {
        return [parent::key()];
      }
    };
    $scenario->setIterator($iterator);

    // Set current to other than first.
    $iterator->seek(1);
    $scenario->preRun();
    $this->assertSame(3, $scenario->getProgress()->getMaxSteps());
    $this->assertSame(0, $scenario->getProgress()->getProgress());

    // Expect the progress to show current step = 1 anyway.
    $scenario->postSearch();
    $this->assertSame(1, $scenario->getProgress()->getProgress());

    $scenario->postRun();
    $this->assertSame(3, $scenario->getProgress()->getProgress());
  }

}
