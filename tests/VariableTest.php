<?php

/**
 * @file
 * Testing utility.
 */

namespace EnvDereference;

/**
 * Tests to ensure that the library works as expected.
 *
 * @todo: Have more tests added to cover customized prefixes.
 * @author ndobromirov
 */
class VariableTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass()
    {
        putenv('MY_VAR_1=1');
        putenv('MY_VAR_2=#MY_VAR_1');
        putenv('MY_VAR=#MY_VAR_2');
    }

    public static function tearDownAfterClass()
    {
        putenv('MY_VAR');
        putenv('MY_VAR_1');
        putenv('MY_VAR_2');
    }

    public function testSingleDereference()
    {
        $this->assertEquals('#MY_VAR_1', Variable::get('MY_VAR'));
    }

    public function testRecursiveDereference()
    {
        $this->assertEquals('1', Variable::getRecursive('MY_VAR'));
    }
}
