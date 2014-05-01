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
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 */
namespace Tests\v1\Integration;

/**
 * The class for testing integration of the API Keys
 *
 * @author Johnathan Pulos
 */
class APIKeysTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The CachedRequest Object
     *
     * @var \PHPToolbox\CachedRequest\CachedRequest
     */
    public $cachedRequest;
    /**
     * The PDO database connection object
     *
     * @var \PHPToolbox\PDODatabase\PDODatabaseConnect
     */
    private $db;

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
        $pdoDb = \PHPToolbox\PDODatabase\PDODatabaseConnect::getInstance();
        $pdoDb->setDatabaseSettings(new \JPAPI\DatabaseSettings);
        $this->db = $pdoDb->getDatabaseInstance();
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
        /**
         * Clear all the api_keys generated by the test
         *
         * @author Johnathan Pulos
         */
        $this->db->query("DELETE FROM `md_api_keys` WHERE `api_usage` = 'testing'");
    }

    /**
     * Tests that APIKey requests without all required fields redirects with the correct required_fields param
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestWithMissingPOSTParamsShouldSetAllRequiredFieldsInURL()
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
    public function testAPIKeyRequestWithMissingPOSTParamsShouldSetRequiredNameFieldInURL()
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
    public function testAPIKeyRequestWithMissingPOSTParamsShouldSetRequiredEmailFieldInURL()
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
    public function testAPIKeyRequestWithMissingPOSTParamsShouldSetRequiredUsageFieldInURL()
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
    public function testAPIKeyRequestShouldReturnIfAllPOSTParamsSupplied()
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
    
    /**
     * Tests that APIKey requests with all fields should set status to 0 (ie. pending)
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestShouldSetStatusToZeroIntially()
    {
        $content = $this->cachedRequest->post(
            "http://joshua.api.local/api_keys",
            array('name' => 'status_should_be_zero', 'email' => 'joe@yahoo.com', 'usage' => 'testing'),
            "status_should_be_zero"
        );
        $statement = $this->db->query("SELECT status from `md_api_keys` WHERE `name` = 'status_should_be_zero'");
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals(0, $data[0]['status']);
    }
    
    /**
     * Tests that APIKey requests with all fields should set an authorize token for the url
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestShouldCreateAnAuthorizeToken()
    {
        $this->cachedRequest->post(
            "http://joshua.api.local/api_keys",
            array('name' => 'should_set_authorize_token', 'email' => 'joe@gmail.com', 'usage' => 'testing'),
            "should_set_authorize_token"
        );
        $statement = $this->db->query(
            "SELECT authorize_token from `md_api_keys` WHERE `name` = 'should_set_authorize_token'"
        );
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertNotNull($data[0]['authorize_token']);
        $this->assertNotEmpty($data[0]['authorize_token']);
    }
    
    /**
     * Tests that APIKey get_my_api_key sets the key to active, and removes the authorize_token
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testGetMyAPIKeySetsProperFields()
    {
        $expectedStatus = 1;
        $this->cachedRequest->post(
            "http://joshua.api.local/api_keys",
            array('name' => 'i_should_become_active', 'email' => 'joe@gmail.com', 'usage' => 'testing'),
            "i_should_become_active"
        );
        $statement = $this->db->query(
            "SELECT authorize_token from `md_api_keys` WHERE `name` = 'i_should_become_active'"
        );
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->cachedRequest->get(
            "http://joshua.api.local/get_my_api_key",
            array('authorize_token' => $data[0]['authorize_token']),
            "i_should_become_active_authorize"
        );
        $statement = $this->db->query("SELECT * from `md_api_keys` WHERE `name` = 'i_should_become_active'");
        $actualData = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertNull($actualData[0]['authorize_token']);
        $this->assertEquals($expectedStatus, $actualData[0]['status']);
    }
    
    /**
     * Tests that APIKey get_my_api_key should not set a suspended status to active
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testGetMyAPIKeyShouldNotChangeKeysThatWereSuspended()
    {
        $expectedStatus = 2;
        $this->cachedRequest->post(
            "http://joshua.api.local/api_keys",
            array('name' => 'i_should_stay_suspended', 'email' => 'joe@gmail.com', 'usage' => 'testing'),
            "i_should_stay_suspended"
        );
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `name` = 'i_should_stay_suspended'");
        $statement = $this->db->query(
            "SELECT authorize_token from `md_api_keys` WHERE `name` = 'i_should_stay_suspended'"
        );
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->cachedRequest->get(
            "http://joshua.api.local/get_my_api_key",
            array('authorize_token' => $data[0]['authorize_token']),
            "i_should_stay_suspended_authorize"
        );
        $statement = $this->db->query("SELECT * from `md_api_keys` WHERE `name` = 'i_should_stay_suspended'");
        $actualData = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertNull($actualData[0]['authorize_token']);
        $this->assertEquals($expectedStatus, $actualData[0]['status']);
    }
}
