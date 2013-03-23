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
/**
 * Get the daily unreached people group for today.  You can specify a specific date using the following parameters
 * as GET vars.
 * month - two digit month
 * day - two digit day
 * 
 * For example, /people_groups/daily_unreached.json?month=01&day=31 will get the people group for Jan. 31st.
 *
 * GET /people_groups/daily_unreached
 * Available Formats JSON & XML
 * 
 * @api
 * @author Johnathan Pulos
 */
$app->get(
    "/people_groups/daily_unreached.:format",
    function ($format) use ($app, $db, $appRequest) {
        /**
         * Get the given parameters, and clean them
         *
         * @author Johnathan Pulos
         */
        $paramMonth = intval(strip_tags($appRequest->params('month')));
        $month = returnPresentOrDefault($paramMonth, Date('n'));
        $paramDay = intval(strip_tags($appRequest->params('day')));
        $day = returnPresentOrDefault($paramDay, Date('j'));
        if ((1 > $month) || ($month > 12)) {
            /**
             * They did not supply a valid month
             *
             * @author Johnathan Pulos
             */
            $app->render("/errors/400.xml.php");
            exit;
        }
        if ((1 > $day) || ($day > 31)) {
            /**
             * They did not supply a valid day
             *
             * @author Johnathan Pulos
             */
            $app->render("/errors/400.xml.php");
            exit;
        }
        $query = "SELECT * FROM jppeoples WHERE LRofTheDayMonth = :month AND LRofTheDayDay = :day LIMIT 1";
        $statement = $db->prepare($query);
        $statement->execute(array('month' => $month, 'day' => $day));
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (empty($data)) {
            $app->render("/errors/404.xml.php");
            exit;
        }
        /**
         * Rename the 10_40Window to a XML friendly tagname
         *
         * @author Johnathan Pulos
         */
        foreach ($data as $key => $val) {
            $data[$key]['Window10_40'] = $val['10_40Window'];
            unset($data[$key]['10_40Window']);
        }
        /**
         * Render the final data
         *
         * @author Johnathan Pulos
         */
        if ($format == 'json') {
            echo json_encode($data);
        } else {
            echo arrayToXML($data, "people_groups", "people_group");
        }
    }
);