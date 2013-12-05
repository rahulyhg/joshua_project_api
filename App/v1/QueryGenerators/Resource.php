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
 * These queries specifically work with the people group Resources data.
 *
 * @package default
 * @author Johnathan Pulos
 **/
class Resource
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
     * The Validator class for checking validations
     *
     * @var object
     * @access private
     */
    private $validator;
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
    private $fieldsToSelectArray = array('ROL3', 'Category', 'WebText', 'URL');
    /**
     * A string that will hold the fields for the Select statement
     *
     * @var string
     * @access private
     */
    private $selectFieldsStatement = '';
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
        $this->validator = new \Utilities\Validator();
        $this->providedParams = $getParams;
        $this->selectFieldsStatement = join(', ', $this->fieldsToSelectArray);
        $this->cleanParams();
    }
    /**
     * Find the People Group Resources using the language_id (ROL3)
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function findAllByLanguageId()
    {
        $this->validator->providedRequiredParams($this->providedParams, array('id'));
        $id = strtolower($this->providedParams['id']);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM jpresources WHERE ROL3 = :id ORDER BY DisplaySeq ASC";
        $this->preparedVariables = array('id' => $id);
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
