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
namespace QueryGenerators;

/**
 * A class that creates the prepared statement, and sets up the variables for a PDO prepared statement query.
 * These queries specifically work with the PeopleGroup data.
 *
 * @package default
 * @author Johnathan Pulos
 */
class PeopleGroup
{
    /**
     * The prepared statement generated by the class to be used with PDO
     *
     * @var string
     * @access public
     */
    public $preparedStatement = "";
    /**
     * The variables for the prepared statement
     *
     * @var array
      * @access public
     */
    public $preparedVariables = array();
    /**
     * The provided parameters passed in from the $_GET params
     *
     * @var array
      * @access private
     */
    private $providedParams = array();
    /**
     * An array of column names for this database table that we want to select in searches.  Simply remove fields you do not want to expose.
     *
     * @var array
     * @access private
     */
    private $fieldsToSelectArray = array('ROG3', 'Ctry', 'PeopleID3', 'ROP3', 'PeopNameInCountry', 'ROG2', 'Continent', 'RegionCode', 'RegionName', 'ISO3', 'LocationInCountry', 'PeopleID1', 'ROP1', 'AffinityBloc', 'PeopleID2', 'ROP2', 'PeopleCluster', 'PeopNameAcrossCountries', 'Population', 'PopulationPercentUN', 'Category', 'ROL3', 'PrimaryLanguageName', 'ROL4', 'PrimaryLanguageDialect', 'NumberLanguagesSpoken', 'ROL3OfficialLanguage', 'OfficialLang', 'SpeakNationalLang', 'BibleStatus', 'BibleYear', 'NTYear', 'PortionsYear', 'TranslationNeedQuestionable', 'JPScale', 'LeastReached', 'LeastReachedBasis', 'GSEC', 'Unengaged', 'API', 'CPI', 'JF', 'AudioRecordings', 'NTOnline', 'GospelRadio', 'CPTeam', 'Church100', 'RLG3', 'PrimaryReligion', 'RLG4', 'ReligionSubdivision', 'PercentAdherents', 'PercentEvangelical', 'PCBuddhism', 'PCDblyProfessing', 'PCEthnicReligions', 'PCHinduism', 'PCIslam', 'PCNonReligious', 'PCOtherSmall', 'PCUnknown', 'PCAnglican', 'PCIndependent', 'PCProtestant', 'PCOrthodox', 'PCOtherChristian', 'PCRomanCatholic', 'StonyGround', 'SecurityLevel', 'UPG153', 'Table71Focus253', 'OriginalJPL', 'RaceCode', 'IndigenousCode', 'LRWebProfile', 'LRofTheDayMonth', 'LRofTheDayDay', 'LRTop100', 'Dalit', 'PhotoAddress', 'PhotoWidth', 'PhotoHeight', 'PhotoAddressExpanded', 'PhotoCredits', 'PhotoCreditURL', 'PhotoCreativeCommons', 'PhotoCopyright', 'PhotoPermission', 'MapAddress', 'MapAddressExpanded', 'MapCredits', 'MapCreditURL', 'MapCopyright', 'MapPermission', 'ProfileTextExists', 'FileAddress', 'FileAddressExpanded', 'FileCredits', 'FileCreditURL', 'FileCopyright', 'FilePermission', 'Top10Ranking', 'RankOverall', 'RankProgress', 'RankPopulation', 'RankLocation', 'RankMinistryTools', 'CountOfCountries', 'CountOfProvinces', 'EthnolinguisticMap', 'MapID', 'V59Country', 'MegablocPC', 'LargeSouthAsianLanguageROL3', 'Longitude', 'Latitude');
    /**
     * A string that will hold the fields for the Select statement
     *
     * @var string
     * @access private
     */
    private $selectFieldsStatement = '';
    /**
     * The table to pull the data from
     *
     * @var string
     * @access private
     */
    private $tableName = "jppeoples";
    /**
     * Construct the class
     *
     * @param array $getParams the params to use for the query.  Each message has required fields, and will throw error
     * if they are missing
     * 
     * @access public
     * @author Johnathan Pulos
     */
    public function __construct($getParams)
    {
        $this->providedParams = $getParams;
        $this->selectFieldsStatement = join(', ', $this->fieldsToSelectArray) . ", 10_40Window as Window10_40";
        $this->cleanParams();
    }
    /**
     * Get the unreached of the day query statement.  Requires a month and day param in the given params.
     * REQUIRES getParams month & day
     * 
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function dailyUnreached()
    {
        $this->validateProvidedParams(array('month', 'day'));
        $month = intval($this->providedParams['month']);
        $day = intval($this->providedParams['day']);
        $this->validateVariableInRange($month, 1, 12);
        $this->validateVariableInRange($day, 1, 31);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM jppeoples WHERE LRofTheDayMonth = :month AND LRofTheDayDay = :day LIMIT 1";
        $this->preparedVariables = array('month' => $month, 'day' => $day);
    }
    /**
     * Find the People Group using the id (PeopleID3), and the country (ROG3)
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function findByIdAndCountry()
    {
        $this->validateProvidedParams(array('id', 'country'));
        $id = intval($this->providedParams['id']);
        $country = strtoupper($this->providedParams['country']);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM jppeoples WHERE PeopleID3 = :id AND ROG3 = :country LIMIT 1";
        $this->preparedVariables = array('id' => $id, 'country' => $country);
    }
    /**
     * Find the People Group by ID (PeopleID3)
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function findById()
    {
        $this->validateProvidedParams(array('id'));
        $id = intval($this->providedParams['id']);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM jppeoples WHERE PeopleID3 = :id";
        $this->preparedVariables = array('id' => $id);
    }
    /**
     * Find all the People Groups using filters passed in the GET params
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function findAllWithFilters()
    {
        $where = "";
        $appendAndOnWhere = false;
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM jppeoples";
        if ($this->paramExists('window1040')) {
            $window1040 = strtoupper($this->providedParams['window1040']);
            $this->validateStringLength($window1040, 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            if ($window1040 == 'Y') {
                $where .= "10_40Window = :window_10_40";
                $this->preparedVariables['window_10_40'] = strtoupper($this->providedParams['window1040']);
            } else if ($window1040 == 'N') {
                $where .= "10_40Window IS NULL";
            } else {
                throw new \InvalidArgumentException("Invalid window1040 value sent.");
            }
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('continents')) {
            $this->validateContinents();
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['continents'], 'ROG2');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('countries')) {
            $this->validateBarSeperatedStringValueLength($this->providedParams['countries'], 2);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['countries'], 'ROG3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('languages')) {
            $this->validateBarSeperatedStringValueLength($this->providedParams['languages'], 3);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['languages'], 'ROL3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('people_id1')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['people_id1'], 'PeopleID1');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('people_id2')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['people_id2'], 'PeopleID2');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('people_id3')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['people_id3'], 'PeopleID3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_anglican')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_anglican'], 'PCAnglican', 'pc_anglican');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_adherent')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_adherent'], 'PercentAdherents', 'pc_adherents');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_buddhist')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_buddhist'], 'PCBuddhism', 'pc_buddhist');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_ethnic_religion')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_ethnic_religion'], 'PCEthnicReligions', 'pc_ethnic_religion');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_evangelical')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_evangelical'], 'PercentEvangelical', 'pc_evangelical');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_hindu')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_hindu'], 'PCHinduism', 'pc_hindu');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_independent')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_independent'], 'PCIndependent', 'pc_independent');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_islam')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_islam'], 'PCIslam', 'pc_islam');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_non_religious')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_non_religious'], 'PCNonReligious', 'pc_non_religious');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_orthodox')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_orthodox'], 'PCOrthodox', 'pc_orthodox');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_other_christian')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_other_christian'], 'PCOtherChristian', 'pc_other_christian');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_other_religions')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_other_religions'], 'PCOtherSmall', 'pc_other_religions');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_protestant')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_protestant'], 'PCProtestant', 'pc_protestant');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_rcatholic')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_rcatholic'], 'PCRomanCatholic', 'pc_rcatholic');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_unknown')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_unknown'], 'PCUnknown', 'pc_unknown');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('population')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['population'], 'Population', 'pop');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('primary_religions')) {
            $religions = explode('|', $this->providedParams['primary_religions']);
            foreach ($religions as $religion) {
                $this->validateVariableInRange($religion, 1, 9, array(3));
            }
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['primary_religions'], 'RLG3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('regions')) {
            $regions = explode('|', $this->providedParams['regions']);
            foreach ($regions as $region) {
                $this->validateVariableInRange($region, 1, 12);
            }
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['regions'], 'RegionCode');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('rop1')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['rop1'], 'ROP1');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('rop2')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['rop2'], 'ROP2');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('rop3')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['rop3'], 'ROP3');
            $appendAndOnWhere = true;
        }
        if ($where != "") {
            $this->preparedStatement .= " WHERE " . $where;
        }
        $this->preparedStatement .= " ORDER BY PeopleID1 ASC ";
        $this->addLimitFilter();
    }
    /**
     * Set the limit and starting variables based on the given limit and page variables
     *
     * @return void
     * @access private
     * @author Johnathan Pulos
     */
    private function addLimitFilter()
    {
        if (($this->paramExists('limit')) && intval($this->providedParams['limit']) > 0) {
            $this->preparedVariables['limit'] = intval($this->providedParams['limit']);
        } else {
            $this->preparedVariables['limit'] = 100;
        }
        if (($this->paramExists('page')) && intval($this->providedParams['page']) > 0) {
            $this->preparedVariables['starting'] = (intval($this->providedParams['page'])*$this->preparedVariables['limit'])-1;
        } else {
            $this->preparedVariables['starting'] = 0;
        }
        $this->preparedStatement .= "LIMIT :starting, :limit";
    }
    /**
     * Generates an IN () statement from a piped string.  It writes the prepared version, and adds the variables to the preparedVariables params.
     * @example 17|23|12 -> IN (17, 23, 12)
     *
     * @param string $str The piped string
     * @param string $columnName the column name that you want to search
     * @return string
     * @access private
     * @author Johnathan Pulos
     */
    private function generateInStatementFromPipedString($str, $columnName)
    {
        $preparedInVars = array();
        $i = 0;
        $stringParts = explode("|", $str);
        foreach ($stringParts as $element) {
            $preparedParamName = str_replace(' ', '', strtolower($columnName)) . '_' . $i;
            array_push($preparedInVars, ':' . $preparedParamName);
            $this->preparedVariables[$preparedParamName] = $element;
            $i = $i+1;
        }
        return $columnName . " IN (" . join(", ", $preparedInVars) . ")";
    }
    /**
     * Generates a BETWEEN statement using a dash separated string.  The string should have either a single integer with no dash, or
     * a min and max separated by a dash.  This will throw an error if you supply too many parameters, or if you minimum is greater
     * then your max.
     *
     * @param string $str The dash separated string min-max
     * @param string $columnName the name of the table column to search
     * @param string $suffix a suffix to be appended to the variable name (Please do not separate with spaces)
     * @return string
     * @throws InvalidArgumentException if the param has too many variables, or the min is greater than the max
     * @access private
     * @author Johnathan Pulos
     */
    private function generateBetweenStatementFromDashSeperatedString($str, $columnName, $suffix)
    {
        $stringValues = explode('-', $str);
        $stringValuesLength = count($stringValues);
        if ($stringValuesLength == 2) {
            $min = floatval($stringValues[0]);
            $max = floatval($stringValues[1]);
            if ($min >= $max) {
                throw new \InvalidArgumentException("A dashed parameter has a minimum greater than it's maximum.");
            }
            $this->preparedVariables["min_" . $suffix] = $min;
            $this->preparedVariables["max_" . $suffix] = $max;
            return $columnName . " BETWEEN :min_" . $suffix . " AND :max_" . $suffix;
        } else if ($stringValuesLength == 1) {
            $this->preparedVariables["total_" . $suffix] = floatval($stringValues[0]);
            return $columnName . " = :total_" . $suffix;
        } else {
            throw new \InvalidArgumentException("A dashed parameter has too many values.");
        }
    }
    /**
     * A shorter method for checking if the array_key_exists
     *
     * @param string $paramName the name of the param your looking for
     * @return void
     * @access private
     * @author Johnathan Pulos
     */
    private function paramExists($paramName)
    {
        return array_key_exists($paramName, $this->providedParams);
    }
    /**
     * Checks if the params were set in the __construct() method of this class on providedParams. If not, then throw an error.
     *
     * @param array $params the keys of the required params
     * @return void
     * @throws InvalidArgumentException if the param does not exist
     * @access private
     * @author Johnathan Pulos
     */
    private function validateProvidedParams($params)
    {
        foreach ($params as $key) {
            if (array_key_exists($key, $this->providedParams) === false) {
                throw new \InvalidArgumentException("Missing the required parameter " . $key);
            }
        }
    }
    /**
     * validates that the provided continent is a correct continent
     *
     * @return void
     * @throws InvalidArgumentException if it continents param has an invalid continent
     * @access private
     * @author Johnathan Pulos
     */
    private function validateContinents()
    {
        $continents = explode('|', $this->providedParams['continents']);
        $validContinents = array('afr', 'asi', 'aus', 'eur', 'nar', 'sop', 'lam');
        foreach ($continents as $continent) {
            $this->validateStringLength($continent, 3);
            if (!in_array(strtolower($continent), $validContinents)) {
                throw new \InvalidArgumentException("Continents provided do not exist.");
            }
        }
    }
    /**
     * Separates a bar separated string and iterates over each element.  Then it validates the length of each element
     *
     * @param string $str the bar separated string
     * @param string $length the length desired
     * @return void
     * @access private
     * @throws InvalidArgumentException if the param is the wrong length
     * @author Johnathan Pulos
     */
    private function validateBarSeperatedStringValueLength($str, $length)
    {
        $elements = explode('|', $str);
        foreach ($elements as $element) {
            $this->validateStringLength($element, $length);
        }
    }
    /**
     * Validates that the string is the correct length
     *
     * @param string $var the string to check
     * @param integer $length the number of characters to test against
     * @return void
     * @throws InvalidArgumentException if the param is the wrong length
     * @access private
     * @author Johnathan Pulos
     */
    private function validateStringLength($var, $length)
    {
        if (strlen($var) !== $length) {
            throw new \InvalidArgumentException("One of your parameters are not the correct length.");
        }
    }
    /**
     * validates a integer is in range
     *
     * @param integer $var the variable to check
     * @param integer $start the start of the range
     * @param integer $end the end of the range
     * @return void
     * @throws InvalidArgumentException if the variable is out of range
     * @access public
     * @author Johnathan Pulos
     */
    private function validateVariableInRange($var, $start, $end, $except = array())
    {
        if ((($var >= $start) && ($var <= $end)) == false) {
            throw new \InvalidArgumentException("One of the provided variables are out of range.");
        }
        if (in_array($var, $except)) {
            throw new \InvalidArgumentException("One of the provided variables is not allowed.");
        }
    }
    /**
     * Cleans the parameters passed to $this->providedParams variable.
     *
     * @return void
     * @access private
     * @author Johnathan Pulos
     */
    private function cleanParams()
    {
        $newValue = array();
        foreach ($this->providedParams as $key => $value) {
            $newValue[$key] = preg_replace('/[^a-z\d\-|\.]/i', '', strip_tags($value));
        }
        $this->providedParams = $newValue;
    }
}
