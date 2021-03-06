# php-env-dereference

Allow dereferencing of environment variables in PHP.

## Overview

This is generally useful, in cloud environments, where different backing
services are injected into PHP with some strange names. At that point the
developer is either using that strange names in his code or starting to do
workarounds.

This particular library solves this based on the idea from variable dereference
feature that is [already in PHP engine](http://php.net/manual/en/language.variables.variable.php).
```
<?php
$a = 'hello';
$$a = 'world';
// Same output.
echo "$a ${$a}\n", "hello world\n";
```

This is very similar to the [phpdotenv](https://github.com/vlucas/phpdotenv)
nested variables functionality, but exposing only it with a simpler syntax and
allowing recursive dereferencing.

## Library examples
```
// We have this environment variables.
putenv('MY_VAR_1=1');
putenv('MY_VAR_2=#MY_VAR_1');
putenv('MY_VAR_3=#MY_VAR_2');
putenv('MY_VAR_4=#MY_VAR_2 #MY_VAR_3');

// Simple dereferencing
echo EnvDereference\Variable::get('MY_VAR_3'); // Should output '#MY_VAR_1'.
echo EnvDereference\Variable::getRecursive('MY_VAR_3'); // Should output '1'.

// Multiple dereferencing
echo EnvDereference\Variable::getEmbedded('MY_VAR_4'); // Should output '#MY_VAR_1 #MY_VAR_2'.
echo EnvDereference\Variable::getEmbeddedRecursive('MY_VAR_4'); // Should output '1 1'.

// Provide defaultds for missing variables
echo EnvDereference\Variable::get('MY_MISSING_VAR', 'default'); // Should output 'default'.
```

## Install
```
composer require ndobromirov/php-env-dereference
```

## Contribute.

The library complies with PSR-2. Validate with ```composer cs```.  
The run tests with ```composer test```.
