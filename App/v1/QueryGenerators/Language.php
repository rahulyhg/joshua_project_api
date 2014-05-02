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
namespace QueryGenerators;

/**
 * Generates the PDO prepared statements and variables for Languages.
 *
 * A class that creates the prepared statement, and sets up the variables for a PDO prepared statement query.
 * Once you call a method like findById,  you can get the prepared statement by reading the class variable
 * $preparedStatement.  You can retrieve the prepared variables by reading the class variable $preparedVariables.
 * So here is an example using the Continents Query Generator to find a continent by id:
 * <pre><code>
 * &lt;?php
 * // Initialize the class, and pass in the id.
 * $continent = new \QueryGenerators\Continent(array('id' => 'AFR'));
 * // Call the method you want.
 * $continent->findById();
 * // Using PDO prepare the statement.
 * $statement = $db->prepare($continent->preparedStatement);
 * // Execute the query with the prepared params.
 * $statement->execute($continent->preparedVariables);
 * // Fetch the final results.
 * $data = $statement->fetchAll(PDO::FETCH_ASSOC);
 * ?&gt;
 * </code></pre>
 *
 * @author Johnathan Pulos
 */
class Language extends QueryGenerator
{
    /**
     * An array of column names for this database table that we want to select in searches.
     * Simply remove fields you do not want to expose.
     *
     * @var     array
     * @access  protected
     */
    protected $fieldsToSelectArray = array(
        'ROL3', 'Language', 'WebLangText', 'Status', 'ROG3', 'HubCountry', 'WorldSpeakers', 'BibleStatus',
        'TranslationNeedQuestionable', 'BibleYear', 'NTYear', 'PortionsYear', 'ROL3Edition14', 'ROL3Edition14Orig',
        'JF', 'JF_URL', 'JF_ID', 'GRN_URL', 'AudioRecordings','GodsStory', 'FCBH_ID', 'JPScale', 'PercentAdherents',
        'PercentEvangelical', 'LeastReached', 'JPPopulation', 'RLG3', 'PrimaryReligion', 'NbrPGICs', 'NbrCountries'
    );
    /**
     * The Database table to pull the data from.
     *
     * @var     string
     * @access  protected
     */
    protected $tableName = "jplanguages";
    /**
     * A string that will hold the default MySQL ORDER BY for the Select statement.
     *
     * @var     string
     * @access  protected
     */
    protected $defaultOrderByStatement = "ORDER BY Language ASC";
    /**
     * An array of table columns (key) and their alias (value).
     *
     * @var     array
     * @access  protected
     **/
    protected $aliasFields = array(
        '4Laws_URL' => 'FourLaws_URL',
        '4Laws' => 'FourLaws'
    );
    /**
     * Construct the Language class.
     *
     * During construction,  the $getParams are checked and inserted in the $providedParams class variable.
     * Some of the methods in this class require certain keys to be set, or it will throw an error.  The comments will
     * state the required keys.
     *
     * @param   array   $getParams  The GET params to use for the query.
     * @return  void
     * @access  public
     * @author  Johnathan Pulos
     */
    public function __construct($getParams)
    {
        parent::__construct($getParams);
        $this->selectFieldsStatement = join(', ', $this->fieldsToSelectArray) .
            ", " . $this->generateAliasSelectStatement();
    }
    /**
     * Find a Language by it's id.
     *
     * Find a language using it's 3 letter ISO code, or Joshua Projects ROL3 code.  You can find a list of codes at
     * <a href='http://goo.gl/gbkgo4' target='_blank'>this website</a>.<br><br><strong>Requires $providedParams['id']:
     * </strong> The three letter ISO code or Joshua Projects ROL3 code.
     *
     * @return  void
     * @access  public
     * @throws  \InvalidArgumentException If the 'id' key is not set on the $providedParams class variable.
     * @author  Johnathan Pulos
     **/
    public function findById()
    {
        $id = strtoupper($this->providedParams['id']);
        $this->validator->providedRequiredParams($this->providedParams, array('id'));
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement .
            " FROM " . $this->tableName . " WHERE ROL3 = :id LIMIT 1";
        $this->preparedVariables = array('id' => $id);
    }
    /**
     * Find all languages using specific filters.
     *
     * Find all languages using a wide range of filters.  To see the types of filters, checkout the Swagger
     * documentation of the API.
     *
     * @return  void
     * @access  public
     * @throws  \InvalidArgumentException When you set a filter, but fail to provide a valid parameter
     * @author  Johnathan Pulos
     **/
    public function findAllWithFilters()
    {
        $where = "";
        $appendAndOnWhere = false;
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName;
        if ($this->paramExists('countries')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['countries'], 2);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['countries'], 'ROG3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('ids')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['ids'], 3);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['ids'], 'ROL3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('has_audio')) {
            $this->validator->stringLength(
                $this->providedParams['has_audio'],
                1
            );
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['has_audio'],
                'AudioRecordings',
                'has_audio'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('has_completed_bible')) {
            $this->validator->stringLength($this->providedParams['has_completed_bible'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBooleanBasedOnIfFieldHasContentOrNot(
                $this->providedParams['has_completed_bible'],
                'BibleYear'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('has_four_laws')) {
            $this->validator->stringLength(
                $this->providedParams['has_four_laws'],
                1
            );
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['has_four_laws'],
                '4Laws',
                'has_four_laws'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('has_jesus_film')) {
            $this->validator->stringLength(
                $this->providedParams['has_jesus_film'],
                1
            );
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['has_jesus_film'],
                'JF',
                'has_jesus_film'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('has_gods_story')) {
            $this->validator->stringLength(
                $this->providedParams['has_gods_story'],
                1
            );
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['has_gods_story'],
                'GodsStory',
                'has_gods_story'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('has_new_testament')) {
            $this->validator->stringLength($this->providedParams['has_new_testament'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBooleanBasedOnIfFieldHasContentOrNot(
                $this->providedParams['has_new_testament'],
                'NTYear'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('has_portions')) {
            $this->validator->stringLength($this->providedParams['has_portions'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBooleanBasedOnIfFieldHasContentOrNot(
                $this->providedParams['has_portions'],
                'PortionsYear'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('jpscale')) {
            $this->validator->barSeperatedStringProvidesAcceptableValues(
                $this->providedParams['jpscale'],
                array('1.1', '1.2', '2.1', '2.2', '3.1', '3.2')
            );
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['jpscale'], 'JPScale');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('least_reached')) {
            $this->validator->stringLength($this->providedParams['least_reached'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['least_reached'],
                'LeastReached',
                'least_reached'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('needs_translation_questionable')) {
            $this->validator->stringLength(
                $this->providedParams['needs_translation_questionable'],
                1
            );
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['needs_translation_questionable'],
                'TranslationNeedQuestionable',
                'questionable_need'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_adherent')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_adherent'],
                'PercentAdherents',
                'pc_adherent'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_evangelical')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_evangelical'],
                'PercentEvangelical',
                'pc_evangelical'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('population')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['population'],
                'JPPopulation',
                'pop'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('primary_religions')) {
            $religions = explode('|', $this->providedParams['primary_religions']);
            foreach ($religions as $religion) {
                $this->validator->integerInRange($religion, 1, 9, array(3));
            }
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['primary_religions'], 'RLG3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('world_speakers')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['world_speakers'],
                'WorldSpeakers',
                'world_speak'
            );
            $appendAndOnWhere = true;
        }
        if ($where != "") {
            $this->preparedStatement .= " WHERE " . $where;
        }
        $this->preparedStatement .= " " . $this->defaultOrderByStatement . " ";
        $this->addLimitFilter();
    }
}
