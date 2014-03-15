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
 * The class for testing integration of the Languages
 *
 * @package default
 * @author Johnathan Pulos
 */
class LanguagesTest extends \PHPUnit_Framework_TestCase
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
     * Tests that you can only access page with an API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowShouldRefuseAccessWithoutAnAPIKey()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages/aar.json",
            array(),
            "aar_up_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can only access page with a version number
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowShouldRefuseAccessWithoutAVersionNumber()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/languages/aar.json",
            array('api_key' => $this->APIKey),
            "versioning_missing_json"
        );
        $this->assertEquals(404, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can not access page without an active API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowShouldRefuseAccessWithoutActiveAPIKey()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/languages/aar.json",
            array('api_key' => $this->APIKey),
            "non_active_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can not access page with a suspended API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowShouldRefuseAccessWithSuspendedAPIKey()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/languages/aar.json",
            array('api_key' => $this->APIKey),
            "suspended_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can only access page with a valid API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowShouldRefuseAccessWithABadAPIKey()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages/aar.json",
            array('api_key' => 'BADKEY'),
            "bad_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
     /**
      * GET /languages/[id].json 
      * test page is available, and delivers JSON
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShouldLanguageShowInJSON()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages/aar.json",
            array('api_key' => $this->APIKey),
            "show_accessible_in_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /languages/[id].xml 
      * test page is available, and delivers XML
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShouldLanguageShowInXML()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages/aar.xml",
            array('api_key' => $this->APIKey),
            "show_accessible_in_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }
    /**
     * GET /languages/[id].json
     * test the page throws an error if you send it an invalid id
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testLanguagesShowShouldThrowErrorIfBadId()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages/bad_id.json",
            array('api_key' => $this->APIKey),
            "show_with_bad_id"
        );
        $this->assertEquals(404, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
     * GET /languages/[id].json
     * test page returns the language requested
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testLanguagesShowShouldRetrieveALanguage()
    {
        $expectedLanguageCode = 'aar';
        $expectedLanguage = 'afar';
        $expectedHubCountry = 'ethiopia';
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages/" . $expectedLanguageCode . ".json",
            array('api_key' => $this->APIKey),
            "show_returns_appropriate_language"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedLanguageCode, strtolower($decodedResponse[0]['ROL3']));
        $this->assertEquals($expectedLanguage, strtolower($decodedResponse[0]['Language']));
        $this->assertEquals($expectedHubCountry, strtolower($decodedResponse[0]['HubCountry']));
    }
    /**
     * Tests that you can only access page with an API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testIndexShouldRefuseAccessWithoutAnAPIKey()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(),
            "index_up_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can only access page with a version number
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testIndexShouldRefuseAccessWithoutAVersionNumber()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/languages.json",
            array('api_key' => $this->APIKey),
            "index_versioning_missing_json"
        );
        $this->assertEquals(404, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can not access page without an active API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testIndexShouldRefuseAccessWithoutActiveAPIKey()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/languages.json",
            array('api_key' => $this->APIKey),
            "index_non_active_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can not access page with a suspended API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testIndexShouldRefuseAccessWithSuspendedAPIKey()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/languages.json",
            array('api_key' => $this->APIKey),
            "index_suspended_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can only access page with a valid API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testIndexShouldRefuseAccessWithABadAPIKey()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array('api_key' => 'BADKEY'),
            "index_bad_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
      * GET /languages.json
      * Language Index should return the correct data
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnLanguageDataInJSON()
    {
        $expectedLanguageCount = 100;
        $expectedFirstLanguage = "a'ou";
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array('api_key' => $this->APIKey),
            "should_return_language_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        $this->assertTrue(is_array($decodedResponse));
        $this->assertFalse(empty($decodedResponse));
        $this->assertEquals($expectedLanguageCount, count($decodedResponse));
        $this->assertEquals($expectedFirstLanguage, strtolower($decodedResponse[0]['Language']));
    }
    /**
      * GET /languages.json
      * Language Index should return the correct limit
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnALimitOfLanguageDataInJSON()
    {
        $expectedLimit = 10;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'   =>  $this->APIKey,
                'limit'     =>  $expectedLimit
            ),
            "should_return_language_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedLimit, count($decodedResponse));
    }
    /**
      * GET /languages.json?ids=bzw|bjf
      * Language Index should return only desired ids
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnIDsRequestedLanguageDataInJSON()
    {
        $expectedIds = 'bzw|bjf';
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'   =>  $this->APIKey,
                'ids'     =>  $expectedIds
            ),
            "should_return_language_by_ids_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertTrue(in_array(strtolower($lang['ROL3']), explode('|', $expectedIds)));
        }
    }
    /**
      * GET /languages.json?ids=bzwp
      * Language Index should return an error if the id is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnErrorIfIdIsWrong()
    {
        $expectedIds = 'bzwp';
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'   =>  $this->APIKey,
                'ids'     =>  $expectedIds
            ),
            "should_return_language_by_wrong_ids_index_json"
        );
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /languages.json?has_new_testament=y
      * Language Index should return only languages with new testaments
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnLanguagesWithNewTestaments()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'               =>  $this->APIKey,
                'has_new_testament'     =>  'Y'
            ),
            "should_return_language_by_ids_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertNotNull($lang['NTYear']);
        }
    }
    /**
      * GET /languages.json?has_new_testament=y
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnErrorIfHasNewTestamentIsWrong()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'               =>  $this->APIKey,
                'has_new_testament'     =>  'NNN'
            ),
            "should_return_language_by_wrong_value_index_json"
        );
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /languages.json?has_portions=y
      * Language Index should return only languages with portions of the Bible
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnLanguagesWithPortionsOfScriptures()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'has_portions'      =>  'Y'
            ),
            "should_return_language_with_portions_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertNotNull($lang['PortionsYear']);
        }
    }
    /**
      * GET /languages.json?has_new_testament=ysss
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnErrorIfHasPortionsIsWrong()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'               =>  $this->APIKey,
                'has_portions'          =>  'NNN'
            ),
            "should_return_language_by_has_portions_wrong_value_index_json"
        );
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /languages.json?has_completed_bible=y
      * Language Index should return only languages with a complete Bible
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnLanguagesWithCompleteScriptures()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'                   =>  $this->APIKey,
                'has_completed_bible'      =>  'Y'
            ),
            "should_return_language_with_complete_bible_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertNotNull($lang['BibleYear']);
        }
    }
    /**
      * GET /languages.json?has_completed_bible=ysss
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnErrorIfHasCompleteBibleIsWrong()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'               =>  $this->APIKey,
                'has_completed_bible'   =>  'NNN'
            ),
            "should_return_language_by_has_completed_wrong_value_index_json"
        );
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /languages.json?needs_translation_questionable=y
      * Language Index should return only languages with Questionable Translation Need
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnLanguagesWithQuestionableTranslation()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'needs_translation_questionable'   =>  'Y'
            ),
            "should_return_language_with_questionable_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertEquals('Y', $lang['TranslationNeedQuestionable']);
        }
    }
    /**
      * GET /languages.json?needs_translation_questionable=ysss
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnErrorIfHasQuestionableIsWrong()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'               =>  $this->APIKey,
                'needs_translation_questionable'   =>  'NNN'
            ),
            "should_return_language_by_has_questionable_wrong_value_index_json"
        );
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /languages.json?has_audio=y
      * Language Index should return only languages with audio resources
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnLanguagesWithAudioResources()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'     =>  $this->APIKey,
                'has_audio'   =>  'Y'
            ),
            "should_return_language_with_audio_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertEquals('Y', $lang['AudioRecordings']);
        }
    }
    /**
      * GET /languages.json?has_audio=ysss
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnErrorIfHasAudioIsWrong()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'     =>  $this->APIKey,
                'has_audio'   =>  'NNN'
            ),
            "should_return_language_by_has_audio_wrong_value_index_json"
        );
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /languages.json?has_four_laws=y
      * Language Index should return only languages with 4 laws
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnLanguagesWithFourLaws()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'has_four_laws'     =>  'Y'
            ),
            "should_return_language_with_four_laws_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertEquals('Y', $lang['FourLaws']);
        }
    }
    /**
      * GET /languages.json?has_four_laws=ysss
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnErrorIfHasFourLawsIsWrong()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'has_four_laws'     =>  'NNN'
            ),
            "should_return_language_by_has_four_laws_wrong_value_index_json"
        );
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /languages.json?has_jesus_film=y
      * Language Index should return only languages with Jesus Film
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnLanguagesWithJesusFilm()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'has_jesus_film'    =>  'Y'
            ),
            "should_return_language_with_jesus_film_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertEquals('Y', $lang['JF']);
        }
    }
    /**
      * GET /languages.json?has_jesus_film=ysss
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnErrorIfHasJesusFilmIsWrong()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'has_jesus_film'    =>  'NNN'
            ),
            "should_return_language_by_has_jesus_film_wrong_value_index_json"
        );
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /languages.json?has_gods_story=y
      * Language Index should return only languages with God's Story
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnLanguagesWithGodsStory()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'has_gods_story'    =>  'Y'
            ),
            "should_return_language_with_gods_story_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertEquals('Y', $lang['GodsStory']);
        }
    }
    /**
      * GET /languages.json?has_gods_story=ysss
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnErrorIfHasGodsStoryIsWrong()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'has_gods_story'    =>  'NNN'
            ),
            "should_return_language_by_has_gods_story_wrong_value_index_json"
        );
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /languages.json?countries=af|cn
      * Language Index should return only languages based on countries spoken in
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnLanguagesBasedOnCountries()
    {
        $expectedCountries = array('af', 'cn');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'countries'         =>  implode("|", $expectedCountries)
            ),
            "should_return_language_based_on_country_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertTrue(in_array(strtolower($lang['ROG3']), $expectedCountries));
        }
    }
    /**
      * GET /languages.json?countries=af|cn
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testLanguageIndexShouldReturnErrorIfCountryIsWrong()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/v1/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'has_gods_story'    =>  'acdc|lklk'
            ),
            "should_return_language_by_countries_wrong_value_index_json"
        );
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
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
