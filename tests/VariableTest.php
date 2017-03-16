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
        putenv('MY_VAR_3=#MY_VAR_2');
        putenv('MY_VAR_4=#MY_VAR_2 #MY_VAR_3');
    }

    public static function tearDownAfterClass()
    {
        putenv('MY_VAR_1');
        putenv('MY_VAR_2');
        putenv('MY_VAR_3');
        putenv('MY_VAR_4');
    }

    public function testSingleDereference()
    {
        $this->assertEquals('1', Variable::get('MY_VAR_2'));
    }

    public function testRecursiveDereference()
    {
        $this->assertEquals('1', Variable::getRecursive('MY_VAR_3'));
    }

    public function testEmbedding()
    {
        $this->assertEquals('#MY_VAR_1 #MY_VAR_2', Variable::getEmbedded('MY_VAR_4'));
    }

    public function testRecursiveEmbedding()
    {
        $this->assertEquals('1 1', Variable::getEmbeddedRecursive('MY_VAR_4'));
    }
}
