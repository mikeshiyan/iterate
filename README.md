# Iterate

[![Build Status](https://travis-ci.org/mikeshiyan/iterator-regex.svg?branch=master)](https://travis-ci.org/mikeshiyan/iterator-regex)

PHP classes and interfaces to iterate `\Iterator`s by a scenario or to perform a
regular expression match on iterator elements by a scenario.

A Scenario is a class that implements the `ScenarioInterface` or extends either
`BaseScenario` or `BaseRegexScenario` abstract class. It receives an `Iterator`
instance and executes the logic in certain moments of the iteration process -
for example, while regex matching is performed - when some pattern matched a
subject, or none matched, or before/after performing a search, etc.

Think of it as of [`array_walk()`](http://php.net/manual/function.array-walk.php)
for iterators or [`iterator_apply()`](http://php.net/manual/function.iterator-apply.php),
but instead of a single callback you provide an object (Scenario) with several
methods. And you can use these Scenarios as plugins.

Iterator is simply any object implementing the [`\Iterator`](http://php.net/manual/class.iterator.php)
interface.

The main class, which runs the process, is `Iterate`. It just needs to be
instantiated and invoked with an iterator and a scenario objects.

Best suited for use as a [Composer](https://getcomposer.org) library.

## Requirements

* PHP &ge; 7.1

## Installation

To add this library to your Composer project:
```
composer require shiyan/iterator-regex
```

## Usage

There are 2 base scenarios included:
* The very basic one to execute some logic on each element of the iterator
unconditionally.
* And the regex based one, which performs a regular expression match (using one
or more patterns) on iterator elements (casted to strings), and allows to
execute different logic depending on what pattern matched or if no patterns
matched.

### Example

Assuming there is an array of strings, `$array`. Iterate over it,
convert all empty strings to 0 (zero), print all strings containing "PHP" and
simply count all other elements.

First, create a scenario class:
```
use Shiyan\Iterate\Scenario\BaseRegexScenario;

class ExampleScenario extends BaseRegexScenario {

  const PATTERN_EMPTY = '/^$/';
  const PATTERN_PHP = '/PHP/';

  public $counter;

  public function getPatterns(): array {
    return [self::PATTERN_EMPTY, self::PATTERN_PHP];
  }

  public function preRun(): void {
    $this->counter = 0;
  }

  public function onMatch(array $matches, string $pattern): void {
    switch ($pattern) {
      case self::PATTERN_EMPTY:
        $this->iterator[$this->iterator->key()] = 0;
        break;

      case self::PATTERN_PHP:
        print $this->iterator->current() . "\n";
        break;
    }
  }

  public function ifNotMatched(): void {
    ++$this->counter;
  }

}
```

And then:
```
use Shiyan\Iterate\Iterate;

// Convert our array of strings to the iterator object.
$iterator = new ArrayIterator($array);
$scenario = new ExampleScenario();
$iterate = new Iterate();

// Invoke iterator-regex with our scenario.
$iterate($iterator, $scenario);
print "Found {$scenario->counter} non-empty, non-PHP strings.\n";

// Let's check that empty strings were converted to zeros.
print_r($iterator->getArrayCopy());

// If we invoke Iterate with the same scenario once again, it won't find empty
// strings anymore. Instead, we'll have a higher number in the $counter property
// of the $scenario object, because the "0" doesn't match our patterns.
$iterate($iterator, $scenario);
print "Found {$scenario->counter} non-empty, non-PHP strings.\n";
```
