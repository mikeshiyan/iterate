<?php

use PHPUnit\Framework\TestCase;
use Shiyan\Iterate\PregLastError;
use Shiyan\Iterate\Scenario\BaseRegexScenario;

class BaseRegexScenarioTest extends TestCase {

  public function testGetIterator() {
    $iterator = new ArrayIterator([]);

    /** @var \Shiyan\Iterate\Scenario\BaseRegexScenario $scenario */
    $scenario = $this->getMockForAbstractClass(BaseRegexScenario::class, [$iterator]);

    $this->assertSame($iterator, $scenario->getIterator());
  }

  public function testOnEach() {
    // Set current to other than first. The second of 3 patterns must match.
    $iterator = new ArrayIterator(['a', 'b', 'c']);
    $iterator->seek(1);

    /** @var \Shiyan\Iterate\Scenario\BaseRegexScenario|\PHPUnit\Framework\MockObject\MockObject $scenario */
    $scenario = $this->getMockForAbstractClass(BaseRegexScenario::class, [$iterator], '', TRUE, TRUE, TRUE, ['getPatterns', 'ifNotMatched']);
    $scenario->method('getPatterns')->willReturn(['/a/', '/b/', '/c/']);
    $scenario->expects($this->once())
      ->method('onMatch')->with(['b'], '/b/');
    $scenario->expects($this->never())->method('ifNotMatched');

    $scenario->onEach();

    // No patterns, ifNotMatched() must be called.
    $scenario = $this->getMockForAbstractClass(BaseRegexScenario::class, [$iterator], '', TRUE, TRUE, TRUE, ['getPatterns', 'ifNotMatched']);
    $scenario->method('getPatterns')->willReturn([]);
    $scenario->expects($this->once())->method('ifNotMatched');

    $scenario->onEach();
  }

  public function testPregMatch() {
    $this->assertSame(['a', 'a'], BaseRegexScenario::pregMatch('/(a)/', 'aa'));
    $this->assertSame(NULL, BaseRegexScenario::pregMatch('/a/', ''));

    $this->expectException(PregLastError::class);
    BaseRegexScenario::pregMatch('/a', '');
  }

  public function testGetPatterns() {
    $iterator = new ArrayIterator([]);

    // Implement getPattern() method.
    /** @var \Shiyan\Iterate\Scenario\BaseRegexScenario|\PHPUnit\Framework\MockObject\MockObject $scenario */
    $scenario = $this->getMockForAbstractClass(BaseRegexScenario::class, [$iterator], '', TRUE, TRUE, TRUE, ['getPattern']);
    $scenario->method('getPattern')->willReturn('/a/');

    $this->assertSame(['/a/'], $scenario->getPatterns());

    // Do not implement getPattern() method - get exception.
    /** @var \Shiyan\Iterate\Scenario\BaseRegexScenario $scenario */
    $scenario = $this->getMockForAbstractClass(BaseRegexScenario::class, [$iterator]);

    $this->expectException(LogicException::class);
    $scenario->getPatterns();
  }

}
