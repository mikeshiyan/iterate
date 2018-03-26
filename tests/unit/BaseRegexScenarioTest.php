<?php

use PHPUnit\Framework\TestCase;
use Shiyan\Iterate\Scenario\BaseRegexScenario;

class BaseScenarioTest extends TestCase {

  public function testGetIterator() {
    $iterator = new ArrayIterator([]);

    /** @var \Shiyan\Iterate\Scenario\BaseRegexScenario $scenario */
    $scenario = $this->getMockForAbstractClass(BaseRegexScenario::class, [$iterator]);

    $this->assertSame($iterator, $scenario->getIterator());
  }

  public function testGetPatterns() {
    $iterator = new ArrayIterator([]);

    // Implement getPattern() method.
    /** @var \Shiyan\Iterate\Scenario\BaseRegexScenario|\PHPUnit\Framework\MockObject\Builder\InvocationMocker $scenario */
    $scenario = $this->getMockForAbstractClass(BaseRegexScenario::class, [$iterator], '', TRUE, TRUE, TRUE, ['getPattern']);
    $scenario->method('getPattern')->willReturn('/a/');

    $this->assertSame(['/a/'], $scenario->getPatterns());

    // Do not implement getPattern() method - get exception.
    /** @var \Shiyan\Iterate\Scenario\BaseRegexScenario|\PHPUnit\Framework\MockObject\Builder\InvocationMocker $scenario */
    $scenario = $this->getMockForAbstractClass(BaseRegexScenario::class, [$iterator]);

    $this->expectException(LogicException::class);
    $scenario->getPatterns();
  }

}
