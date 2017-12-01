<?php

use PHPUnit\Framework\TestCase;
use Shiyan\IteratorRegex\Scenario\BaseScenario;

class BaseScenarioTest extends TestCase {

  public function testGetIterator() {
    $iterator = new ArrayIterator([]);

    /** @var \Shiyan\IteratorRegex\Scenario\BaseScenario $scenario */
    $scenario = $this->getMockForAbstractClass(BaseScenario::class, [$iterator]);

    $this->assertSame($iterator, $scenario->getIterator());
  }

  public function testGetPatterns() {
    $iterator = new ArrayIterator([]);

    // Implement getPattern() method.
    /** @var \Shiyan\IteratorRegex\Scenario\BaseScenario|\PHPUnit\Framework\MockObject\Builder\InvocationMocker $scenario */
    $scenario = $this->getMockForAbstractClass(BaseScenario::class, [$iterator], '', TRUE, TRUE, TRUE, ['getPattern']);
    $scenario->method('getPattern')->willReturn('/a/');

    $this->assertSame(['/a/'], $scenario->getPatterns());

    // Do not implement getPattern() method - get exception.
    /** @var \Shiyan\IteratorRegex\Scenario\BaseScenario|\PHPUnit\Framework\MockObject\Builder\InvocationMocker $scenario */
    $scenario = $this->getMockForAbstractClass(BaseScenario::class, [$iterator]);

    $this->expectException(LogicException::class);
    $scenario->getPatterns();
  }

}
