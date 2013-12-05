<?php
/**
 * This file is part of Joshua Project API.
 * 
 * Joshua Project API is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Joshua Project API is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see 
 * <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @copyright Copyright 2013 Missional Digerati
 * 
 */
namespace Tests\v1\Unit\Utilities;

/**
 * Test the Validator Utility
 *
 * @author Johnathan Pulos
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The PDO database connection object
     *
     * @var object
     */
    private $db;
    /**
     * The Validator object
     *
     * @var object
     */
    private $validator;
    /**
     * Setup the test methods
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function setUp()
    {
        $pdoDb = \PHPToolbox\PDODatabase\PDODatabaseConnect::getInstance();
        $pdoDb->setDatabaseSettings(new \JPAPI\DatabaseSettings);
        $this->db = $pdoDb->getDatabaseInstance();
        $this->validator = new \Utilities\Validator();
    }
    /**
     * Tests that providedRequiredParams throws the correct error if a param is missing
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testShouldErrorIfprovidedRequiredParamsFindsMissingParam()
    {
        $suppliedParams = array('name' => 'John');
        $requiredKeys = array('name', 'address');
        $this->validator->providedRequiredParams($suppliedParams, $requiredKeys);
    }
    /**
     * Tests that providedRequiredParams throws the correct error if a param is not set
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testShouldErrorIfprovidedRequiredParamsFindsAnUnsetParam()
    {
        $suppliedParams = array('name' => 'John', 'address' => null);
        $requiredKeys = array('name', 'address');
        $this->validator->providedRequiredParams($suppliedParams, $requiredKeys);
    }
    /**
     * Tests that providedRequiredParams does not throw error if all required fields are passed
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldNotErrorIfprovidedRequiredParamsFindsNoMissingParam()
    {
        $suppliedParams = array('name' => 'John', 'address' => '122 East West');
        $requiredKeys = array('name', 'address');
        $this->validator->providedRequiredParams($suppliedParams, $requiredKeys);
    }
    /**
     * Tests that barSeperatedStringProvidesAcceptableValues() throws error if not in the given array
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testBarSeperatedStringProvidesAcceptableValuesShouldThrowErrorIfNotAcceptableValues()
    {
        $suppliedBarSeperatedParam = '2.3|34.4';
        $acceptableValues = array('1.1', '5.4');
        $this->validator->barSeperatedStringProvidesAcceptableValues($suppliedBarSeperatedParam, $acceptableValues);
    }
    /**
     * Tests that barSeperatedStringProvidesAcceptableValues() returns succesfully if all values are acceptable
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testBarSeperatedStringProvidesAcceptableValuesShouldReturnIfAllAcceptableValues()
    {
        $suppliedBarSeperatedParam = '2.3|34.4';
        $acceptableValues = array('2.3', '34.4');
        $this->validator->barSeperatedStringProvidesAcceptableValues($suppliedBarSeperatedParam, $acceptableValues);
    }
    /**
     * stringLength() should error if the character length is incorrect
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testStringLengthShouldThrowErrorIfLengthIsIncorrect()
    {
        $testString = 'I Love Ice Cream!';
        $requiredLength = 5;
        $this->validator->stringLength($testString, $requiredLength);
    }
    /**
     * stringLength() should not throw an error if the character length is correct
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testStringLengthShouldNotThrowErrorIfLengthIsCorrect()
    {
        $testString = 'I Love Ice Cream!';
        $requiredLength = 17;
        $this->validator->stringLength($testString, $requiredLength);
    }
    /**
     * stringLengthValuesBarSeperatedString() should error if the character length is incorrect
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testStringLengthValuesBarSeperatedStringShouldThrowErrorIfLengthIsIncorrect()
    {
        $testString = 'Freee|Treee|Key';
        $requiredLength = 5;
        $this->validator->stringLengthValuesBarSeperatedString($testString, $requiredLength);
    }
    /**
     * stringLengthValuesBarSeperatedString() should not error if the character length is correct
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testStringLengthValuesBarSeperatedStringShouldNotThrowErrorIfLengthIsCorrect()
    {
        $testString = 'Freee|Treee|Keyee';
        $requiredLength = 5;
        $this->validator->stringLengthValuesBarSeperatedString($testString, $requiredLength);
    }
}
