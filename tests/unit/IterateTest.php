<?php

use PHPUnit\Framework\TestCase;
use Shiyan\Iterate\Iterate;
use Shiyan\Iterate\Scenario\BaseRegexScenario;

class IterateTest extends TestCase {

  public function testInvoke() {
    $iterate = new Iterate();

    // Empty iterator.
    $scenario = $this->createMock(BaseRegexScenario::class);
    $scenario->expects($this->once())->method('preRun');
    $scenario->expects($this->once())->method('postRun');
    $scenario->expects($this->never())->method('preSearch');

    $iterate(new ArrayIterator([]), $scenario);

    // Set current to other than first, but expect search in all of them.
    $iterator = new ArrayIterator(['a', 'b', 'c']);
    $iterator->seek(1);

    $scenario = $this->createMock(BaseRegexScenario::class);
    $scenario->expects($this->once())->method('preRun');
    $scenario->expects($this->once())->method('postRun');
    $scenario->expects($this->exactly(3))->method('preSearch');
    $scenario->expects($this->exactly(3))->method('postSearch');

    $iterate($iterator, $scenario);
  }

}
