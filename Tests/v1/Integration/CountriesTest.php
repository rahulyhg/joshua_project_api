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
 * The class for testing integration of the Countries
 *
 * @package default
 * @author Johnathan Pulos
 */
class CountriesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The CachedRequest Object
     *
     * @var object
     */
    public $cachedRequest;
    /**
     * The APIKey to access the API
     *
     * @var string
     * @access private
     **/
    private $APIKey = '';
    /**
     * The PDO database connection object
     *
     * @var object
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
        $this->setAPIKey();
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
     * Tests that you get a 404 Error if you do not pass an id to the country -> show action
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testCountryShowShouldRefuseAccessWithoutAValidID()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries/1234.json",
            array('api_key' => $this->APIKey),
            "country_show_without_id"
        );
        $this->assertEquals(404, $this->cachedRequest->responseCode);
    }
    /**
      * GET /countries/usa.json 
      * test page is available, and delivers JSON
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testCountryShowShouldBeAccessableByJSON()
    {
        $expectedCountry = "US";
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries/" . $expectedCountry . ".json",
            array('api_key' => $this->APIKey),
            "should_return_country_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /countries/usa.xml 
      * test page is available, and delivers XML
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testCountryShowShouldBeAccessableByXML()
    {
        $expectedCountry = "US";
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries/" . $expectedCountry . ".xml",
            array('api_key' => $this->APIKey),
            "should_return_country_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }
    /**
      * GET /countries/usa.json
      * Country Show should return the correct country data
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testCountryShowShouldReturnCountryInJSON()
    {
        $expectedCountry = "US";
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries/" . $expectedCountry . ".json",
            array('api_key' => $this->APIKey),
            "should_return_country_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        $this->assertTrue(is_array($decodedResponse));
        $this->assertFalse(empty($decodedResponse));
        $this->assertEquals($expectedCountry, $decodedResponse[0]['ISO2']);
    }
    /**
      * GET /countries.json 
      * test page is available, and delivers JSON
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testCountryIndexShouldBeAccessableByJSON()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey),
            "should_return_country_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /countries.xml 
      * test page is available, and delivers XML
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testCountryIndexShouldBeAccessableByXML()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.xml",
            array('api_key' => $this->APIKey),
            "should_return_country_index_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }
    /**
      * GET /countries.json
      * Country Index should return the correct data
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testCountryIndexShouldReturnCountryDataInJSON()
    {
        $expectedCountryCount = 100;
        $expectedFirstCountry = 'Afghanistan';
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey),
            "should_return_country_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        $this->assertTrue(is_array($decodedResponse));
        $this->assertFalse(empty($decodedResponse));
        $this->assertEquals($expectedCountryCount, count($decodedResponse));
        $this->assertEquals($expectedFirstCountry, $decodedResponse[0]['Country']);
    }
    /**
      * GET /countries.json?limit=10
      * Country Index should return the correct data with a limit
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testCountryIndexShouldReturnASetLimitOfCountryData()
    {
        $expectedCountryCount = 10;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'limit' => $expectedCountryCount),
            "should_return_country_index_with_limit_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedCountryCount, count($decodedResponse));
    }
    /**
      * GET /countries.json?ids=US|AF|AL
      * Country Index should return the correct data when setting the ids parameter
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testCountryIndexShouldReturnCountriesFilteredByIDs()
    {
        $expectedIDs = array('us', 'af', 'al');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'ids' => join('|', $expectedIDs)),
            "should_return_country_index_with_ids_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $country) {
            $this->assertTrue(in_array(strtolower($country['ROG3']), $expectedIDs));
        }
    }
    /**
      * GET /countries.json?continents=EUR|NAR
      * Country Index should return the correct data when filtering by continents
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testCountryIndexShouldReturnCountriesFilteredByContinents()
    {
        $expectedContinents = array('eur', 'nar');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'continents' => join('|', $expectedContinents)),
            "should_return_country_index_with_continents_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $country) {
            $this->assertTrue(in_array(strtolower($country['ROG2']), $expectedContinents));
        }
    }
    /**
      * GET /countries.json?regions=1|5
      * Country Index should return the correct data when filtering by regions
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testCountryIndexShouldReturnCountriesFilteredByRegions()
    {
        $expectedRegions = array(1, 5);
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'regions' => join('|', $expectedRegions)),
            "should_return_country_index_with_regions_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $country) {
            $this->assertTrue(in_array(strtolower($country['RegionCode']), $expectedRegions));
        }
    }
    /**
      * GET /countries.json?window1040=n
      * Country Index should return the correct data when filtering by window1040
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testCountryIndexShouldReturnCountriesFilteredByWindow1040()
    {
        $expectedWindow1040 = 'y';
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'window1040' => $expectedWindow1040),
            "should_return_country_index_with_window_1040_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $country) {
            $this->assertEquals(strtolower($country['Window10_40']), $expectedWindow1040);
        }
    }
    /**
      * GET /countries.json?primary_languages=por
      * Country Index should return the correct data when filtering by primary_languages
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testCountryIndexShouldReturnCountriesFilteredByPrimaryLanguages()
    {
        $expectedPrimaryLanguages = array('por');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'primary_languages' => join('|', $expectedPrimaryLanguages)),
            "should_return_country_index_with_primary_languages_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $country) {
            $this->assertTrue(in_array(strtolower($country['ROL3OfficialLanguage']), $expectedPrimaryLanguages));
        }
    }
    /**
     * GET /countries.json?population=10000-20000
     * test page filters by a set range of population
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnCountriesFliteredByAMinAndMaxPopulation()
    {
        $expectedMin = 10000;
        $expectedMax = 20000;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'population' => $expectedMin."-".$expectedMax),
            "filter_by_pop_in_range_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $country) {
            $this->assertLessThanOrEqual($expectedMax, intval($country['Population']));
            $this->assertGreaterThanOrEqual($expectedMin, intval($country['Population']));
        }
    }
    /**
     * GET /countries.json?population=600
     * test page filters by an exact population
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnCountriesFliteredByAnExactPopulation()
    {
        $expectedPopulation = 600;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'population' => $expectedPopulation),
            "filter_by_pop_exact_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $country) {
            $this->assertEquals($expectedPopulation, intval($country['Population']));
        }
    }
    /**
     * GET /countries.json?primary_religions=1|7
     * test page filters by primary religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnCountriesFliteredByPrimaryReligions()
    {
        $expectedReligions = array(1 => 'christianity', 7 => 'non-religious');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'primary_religions' => join('|', array_keys($expectedReligions))),
            "filter_by_primary_religion_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertTrue(in_array(strtolower($countryData['PrimaryReligion']), array_values($expectedReligions)));
            $this->assertTrue(in_array($countryData['RLG3'], array_keys($expectedReligions)));
        }
    }
    /**
     * GET /countries.json?primary_religions=7
     * test page filters by an exact primary religion
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnCountriesFliteredByASinglePrimaryReligion()
    {
        $expectedReligions = array(7 => 'non-religious');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'primary_religions' => join('|', array_keys($expectedReligions))),
            "filter_by_exact_primary_religion_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertTrue(in_array(strtolower($countryData['PrimaryReligion']), array_values($expectedReligions)));
            $this->assertTrue(in_array($countryData['RLG3'], array_keys($expectedReligions)));
        }
    }
    /**
     * GET /countries.json?pc_christianity=10-20
     * test page filters by a range of percentage of christianity
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnCountriesFliteredByRangeOfPCChristianity()
    {
        $expectedMin = 10;
        $expectedMax = 20;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'pc_christianity' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_percent_christianity_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentChristianity']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentChristianity']));
        }
    }
    /**
     * GET /countries.json?pc_evangelical=0-20
     * test page filters by a range of percentage of christianity
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnCountriesFliteredByRangeOfPCEvangelical()
    {
        $expectedMin = 0;
        $expectedMax = 20;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'pc_evangelical' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_percent_evangelical_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentEvangelical']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentEvangelical']));
        }
    }
    /**
     * GET /countries.json?pc_buddhist=10-25
     * test page filters by a range of percentage of buddhist
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnCountriesFliteredByRangeOfPCBuddhist()
    {
        $expectedMin = 10;
        $expectedMax = 25;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'pc_buddhist' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_buddhist_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentBuddhism']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentBuddhism']));
        }
    }
    /**
     * GET /countries.json?pc_ethnic_religion=1-10
     * test page filters by a range of percentage of ethnic religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnCountriesFliteredByRangeOfPCEthnicReligions()
    {
        $expectedMin = 1;
        $expectedMax = 10;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'pc_ethnic_religion' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_ethnic_religion_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentEthnicReligions']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentEthnicReligions']));
        }
    }
    /**
     * GET /countries.json?pc_hindu=15-35
     * test page filters by a range of percentage of hindu
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnCountriesFliteredByRangeOfPCHindu()
    {
        $expectedMin = 15;
        $expectedMax = 35;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'pc_hindu' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_hindu_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentHinduism']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentHinduism']));
        }
    }
    /**
     * GET /countries.json?pc_islam=85-100
     * test page filters by a range of percentage of Islam
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnCountriesFliteredByRangeOfPCIslam()
    {
        $expectedMin = 85;
        $expectedMax = 100;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'pc_islam' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_islam_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentIslam']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentIslam']));
        }
    }
    /**
     * GET /countries.json?pc_non_religious=0-10
     * test page filters by a range of percentage of Non Religious
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnCountriesFliteredByRangeOfPCNonReligious()
    {
        $expectedMin = 0;
        $expectedMax = 10;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'pc_non_religious' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_non_religious_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentNonReligious']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentNonReligious']));
        }
    }
    /**
     * GET /countries.json?pc_other_religions=2-3
     * test page filters by a range of percentage of Other Religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnCountriesFliteredByRangeOfPCOtherReligions()
    {
        $expectedMin = 2;
        $expectedMax = 3;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'pc_other_religions' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_other_religions_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentOtherSmall']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentOtherSmall']));
        }
    }
    /**
     * GET /countries.json?pc_other_religions=0-0.14
     * test page filters by a range of percentage of Unknown
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnCountriesFliteredByRangeOfPCUnknown()
    {
        $expectedMin = 0;
        $expectedMax = 0.14;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/countries.json",
            array('api_key' => $this->APIKey, 'pc_unknown' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_unknown_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentUnknown']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentUnknown']));
        }
    }
    /**
     * gets an APIKey by sending a request to the /api_keys url
     *
     * @return string
     * @author Johnathan Pulos
     **/
    private function setAPIKey()
    {
        if ($this->APIKey == "") {
            $newAPIKey = generateRandomKey(12);
            $apiKeyValues = array(  'name' => 'Test API',
                                    'email' => 'joe@testing.com',
                                    'organization' => 'Testing.com',
                                    'website' => 'http://www.testing.com',
                                    'api_usage' => 'testing',
                                    'api_key' => $newAPIKey,
                                    'status' => 1
                                );
            /**
             * Create a new API Key
             *
             * @author Johnathan Pulos
             */
            $query = "INSERT INTO `md_api_keys` (name, email, organization, website, api_usage, api_key, status) 
                        VALUES (:name, :email, :organization, :website, :api_usage, :api_key, :status)";
            try {
                $statement = $this->db->prepare($query);
                $statement->execute($apiKeyValues);
                $this->APIKey = $newAPIKey;
            } catch (PDOException $e) {
                echo "Unable to set the API Key!";
                die();
            }
        }
    }
}
