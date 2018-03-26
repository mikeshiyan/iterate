<?php

use PHPUnit\Framework\TestCase;
use Shiyan\Iterate\IteratorRegex;
use Shiyan\Iterate\PregLastError;
use Shiyan\Iterate\Scenario\BaseRegexScenario;

class IteratorRegexTest extends TestCase {

  public function testInvoke() {
    $iterator_regex = new IteratorRegex();

    // Empty iterator.
    $scenario = $this->createMock(BaseRegexScenario::class);
    $scenario->method('getIterator')->willReturn(new ArrayIterator([]));
    $scenario->expects($this->once())->method('preRun');
    $scenario->expects($this->once())->method('postRun');
    $scenario->expects($this->never())->method('preSearch');

    $iterator_regex($scenario);

    // Set current to other than first, but expect search in all of them.
    $iterator = new ArrayIterator(['a', 'b', 'c']);
    $iterator->seek(1);

    $scenario = $this->createMock(BaseRegexScenario::class);
    $scenario->method('getIterator')->willReturn($iterator);
    $scenario->expects($this->once())->method('preRun');
    $scenario->expects($this->once())->method('postRun');
    $scenario->expects($this->exactly(3))->method('preSearch');
    $scenario->expects($this->exactly(3))->method('postSearch');

    $iterator_regex($scenario);
  }

  public function testSearchInCurrent() {
    $iterator_regex = $this->getMockForAbstractClass(IteratorRegex::class);

    $search_in_current = (new \ReflectionObject($iterator_regex))->getMethod('searchInCurrent');
    $search_in_current->setAccessible(TRUE);

    // Set current to other than first.
    $iterator = new ArrayIterator(['a', 'b', 'c']);
    $iterator->seek(1);

    // The second of 3 patterns must match.
    $scenario = $this->createMock(BaseRegexScenario::class);
    $scenario->method('getPatterns')->willReturn(['/a/', '/b/', '/c/']);
    $scenario->expects($this->exactly(2))
      ->method('getIterator')->willReturn($iterator);
    $scenario->expects($this->once())
      ->method('onMatch')->with(['b'], '/b/');
    $scenario->expects($this->never())->method('ifNotMatched');

    $search_in_current->invoke($iterator_regex, $scenario);

    // No patterns, ifNotMatched() must be called.
    $scenario = $this->createMock(BaseRegexScenario::class);
    $scenario->method('getPatterns')->willReturn([]);
    $scenario->expects($this->once())->method('ifNotMatched');

    $search_in_current->invoke($iterator_regex, $scenario);
  }

  public function testPregMatch() {
    $this->assertSame(['a', 'a'], IteratorRegex::pregMatch('/(a)/', 'aa'));
    $this->assertSame(NULL, IteratorRegex::pregMatch('/a/', ''));

    $this->expectException(PregLastError::class);
    IteratorRegex::pregMatch('/a', '');
  }

}
