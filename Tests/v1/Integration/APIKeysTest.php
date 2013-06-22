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
namespace Tests\v1\Integration;

/**
 * The class for testing integration of the People Groups
 *
 * @package default
 * @author Johnathan Pulos
 */
class APIKeysTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The CachedRequest Object
     *
     * @var object
     */
    public $cachedRequest;

    /**
     * Set up the test class
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function setUp()
    {
        $this->cachedRequest = new \PHPToolbox\CachedRequest\CachedRequest;
        $this->cachedRequest->cacheDirectory =
            __DIR__ .
            DIRECTORY_SEPARATOR . ".." .
            DIRECTORY_SEPARATOR . ".." .
            DIRECTORY_SEPARATOR . "Support" .
            DIRECTORY_SEPARATOR . "cache" .
            DIRECTORY_SEPARATOR;
    }
    /**
     * Runs at the end of each test
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function tearDown()
    {
        $this->cachedRequest->clearCache();
    }

    /**
     * Tests that APIKey requests without all required fields redirects with the correct required_fields param
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKequestWithMissingAllPOSTParamsShouldSetRequiredFieldsParam()
    {
        $expectedURL = "http://joshua.api.local/?required_fields=name|email|usage";
        $this->cachedRequest->post(
            "http://joshua.api.local/api_keys",
            array('name' => '', 'email' => '', 'usage' => ''),
            "api_keys_required_fields"
        );
        $actualURL = $this->cachedRequest->lastVisitedURL;
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * Tests that APIKey requests without name field redirects with the correct required_fields param
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKequestWithMissingNamePOSTParamsShouldSetRequiredFieldsParam()
    {
        $expectedURL = "http://joshua.api.local/?required_fields=name&email=joe%40yahoo.com&usage=testing";
        $this->cachedRequest->post(
            "http://joshua.api.local/api_keys",
            array('name' => '', 'email' => 'joe@yahoo.com', 'usage' => 'testing'),
            "api_keys_required_fields"
        );
        $actualURL = $this->cachedRequest->lastVisitedURL;
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * Tests that APIKey requests without email field redirects with the correct required_fields param
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKequestWithMissingEmailPOSTParamsShouldSetRequiredFieldsParam()
    {
        $expectedURL = "http://joshua.api.local/?required_fields=email&name=joe&usage=testing";
        $this->cachedRequest->post(
            "http://joshua.api.local/api_keys",
            array('name' => 'joe', 'email' => '', 'usage' => 'testing'),
            "api_keys_required_fields"
        );
        $actualURL = $this->cachedRequest->lastVisitedURL;
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * Tests that APIKey requests without usage field redirects with the correct required_fields param
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKequestWithMissingUsagePOSTParamsShouldSetRequiredFieldsParam()
    {
        $expectedURL = "http://joshua.api.local/?required_fields=usage&name=joe&email=joe%40yahoo.com";
        $this->cachedRequest->post(
            "http://joshua.api.local/api_keys",
            array('name' => 'joe', 'email' => 'joe@yahoo.com', 'usage' => ''),
            "api_keys_required_fields"
        );
        $actualURL = $this->cachedRequest->lastVisitedURL;
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * Tests that APIKey requests with all fields returns an api_key
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKequestShouldSetReturnAnAPIKey()
    {
        $this->cachedRequest->post(
            "http://joshua.api.local/api_keys",
            array('name' => 'joe', 'email' => 'joe@yahoo.com', 'usage' => 'testing'),
            "api_keys_required_fields"
        );
        $lastVisitedURL = $this->cachedRequest->lastVisitedURL;
        $APIKeyCheck = preg_match('/api_key=(.*)/', $lastVisitedURL, $matches);
        $this->assertFalse(empty($matches));
        $this->assertTrue(isset($matches[1]));
        $this->assertFalse($matches[1] == "");
    }
}