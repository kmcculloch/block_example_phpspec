### Block Example Using PHPSpec

This module rewrites a hook from the Drupal 7 block_example module to demonstrate PHPSpec BDD techniques.

Kevin McCulloch

p.kevin.mcculloch@gmail.com

## Usage

Install Composer:

`https://getcomposer.org/download/`

cd to the root of this repository and install PHPSpec:

`composer install`

Run the PHPSpec tests:

`vendor/bin/phpspec run`

## Why?

This is example code for a session on Drupal and automated testing that I gave
at BADCamp 2016:

https://2016.badcamp.net/session/automated-testing-drupal-and-phpspec

This code demonstrates the technique of inserting small behavior-driven
components into Drupal via function hooks, and wrapping calls back to Drupal
in a service object that can be mocked for testing. It is meant to provide a
simple recipe that you can follow to introduce PHPSpec into a Drupal 7 project.
It doesn't illustrate the full power of PHPSpec as a development tool, but if
you adopt PHPSpec and work with it to build more complicated components I trust
that you'll begin to see its value.

To get a better idea of why you might want to use PHPSpec for your projects,
I recommend starting with the documentation at http://www.phpspec.net/.
PHPSpec is an opinionated tool. Unlike PHPUnit, it has a testing syntax that
will nudge your code toward a good component-oriented architecture. In
particular it will reward you for implementing dependency injection, a design
pattern that is central to Drupal 8 and worth porting back to Drupal 7.

## Learning

Start by comparing the original `block_example_block_view_alter` with its
refactored version, `block_example_phpspec_block_view_alter`:

```php
function block_example_block_view_alter(&$data, $block) {
  // We'll search for the string 'uppercase'.
  if ((!empty($block->title) && stristr($block->title, 'uppercase')) || (!empty($data['subject']) && stristr($data['subject'], 'uppercase'))) {
    // This will uppercase the default title.
    $data['subject'] = isset($data['subject']) ? drupal_strtoupper($data['subject']) : '';
    // This will uppercase a title set in the UI.
    $block->title = isset($block->title) ? drupal_strtoupper($block->title) : '';
  }
}
```

```php
use Drupal\block_example_phpspec\DrupalServiceWrapper;
use Drupal\block_example_phpspec\BlockProcessor;

function block_example_phpspec_block_view_alter(&$data, $block) {
  $drupal = new DrupalServiceWrapper();
  $processor = new BlockProcessor($drupal);

  // We'll search for the string 'uppercase'.
  if ($processor->findUppercase($data, $block)) {
    // This will uppercase the default title.
    $data = $processor->setDataToUppercase($data);
    // This will uppercase a title set in the UI.
    $block = $processor->setBlockToUppercase($block);
  }
}
```

You'll notice that the conditional and data processing logic from the
original hook have been replaced with calls to a BlockProcessor object.
You'll also notice that the BlockProcessor object constructor required a
DrupalServiceWrapper dependency.

Next, take a look at src/BlockProcessor.php. It contains all of the logic from
the original hook, refactored into small functions. Pay particular attention
to the calls to the DrupalServiceWrapper which wrap the calls to
`drupal_strtoupper` inside of a new function, call().

When writing components like BlockProcessor, you never want to
call Drupal functions in the global namespace directly. Passing all of
your interactions with Drupal through a service wrapper decouples your
component from Drupal. This ensures that it can be tested in Drupal's absence,
which is valuable enough, but more importantly it enforces an abstraction
barrier between the code you write in order to satisfy business logic and the
code you write in order to hook into Drupal. You should think of a Drupal
hook as a place where you spin up a completely independent, tiny application
that can use a special interface to talk to Drupal when it needs to. Drupal
thus becomes an external service that your component uses, rather than the
environment your component lives inside and can't do without.

Now look at the PHPSpec test definition inside
`spec/Drupal/block_example_phpspec/BlockProcessorSpec.php`. For test
purposes, the let() function handles the creation of a mock Drupal object
that can handle our component's request to call drupal_strtoupper().

## Autoloading

To use PHPSpec in Drupal 7 you need to introduce namespaces into your code.
For a simple module like this, I recommend the xautoload module. All you need
to do is add xautoload as a dependency in your mymodule.info file and it
will load all of the code in your module's src/ directory into the
Drupal\mymodule namespace automatically.

Since PHPSpec runs on the command line, it uses Composer autoloading. Check out
the "autoload-dev" directive in `composer.json` to see the autoload mapping
PHPSpec uses to load and test our code.
