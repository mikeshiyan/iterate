# Iterator Regex

[![Build Status](https://travis-ci.org/mikeshiyan/iterator-regex.svg?branch=master)](https://travis-ci.org/mikeshiyan/iterator-regex)

PHP classes and interfaces to perform a regular expression match on iterator
elements by the scenario.

Scenario is an object that implements the `ScenarioInterface` or extends the
`BaseScenario` abstract class. It stores an iterator instance and has methods
describing logic that needs to be executed in certain moments while regex
matching is performed, - for example, when some pattern matched a subject, or
none matched, or before/after performing a search, etc.

Iterator is any object implementing `Iterator` interface.

The main class, which performs matching, is `IteratorRegex`. It just needs to be
instantiated and invoked with a scenario instance.

Best suited for use as a [Composer](https://getcomposer.org) library.

## Requirements

* PHP &ge; 7.1

## Installation

To add this library to your Composer project:
```
composer require shiyan/iterator-regex
```

## Usage

Example. Assuming there is an array of strings, `$array`. Iterate over it,
convert all empty strings to 0 (zero), print all strings containing "PHP" and
simply count all other elements.

First, create a scenario class:
```
use Shiyan\IteratorRegex\Scenario\BaseScenario;

class ExampleScenario extends BaseScenario {

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
use Shiyan\IteratorRegex\IteratorRegex;

// Convert our array of strings to the iterator object, and supply it to our
// scenario object.
$iterator = new ArrayIterator($array);
$scenario = new ExampleScenario($iterator);
$iterator_regex = new IteratorRegex();

// Invoke iterator-regex with our scenario.
$iterator_regex($scenario);
print "Found {$scenario->counter} non-empty, non-PHP strings.\n";

// Let's check that empty strings were converted to zeros.
print_r($scenario->getIterator()->getArrayCopy());

// If we invoke iterator-regex with the same scenario once again, it won't find
// empty strings anymore. Instead, we'll have a higher number in the $counter
// property of the $scenario object.
$iterator_regex($scenario);
print "Found {$scenario->counter} non-empty, non-PHP strings.\n";
```
