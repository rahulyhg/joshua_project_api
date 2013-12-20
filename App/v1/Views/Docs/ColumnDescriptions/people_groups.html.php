<!DOCTYPE html>
<html>
  <head>
      <title>Joshua Project API - Version 1 Documentation</title>
<?php
    include($PUBLIC_DIRECTORY . '/partials/site_wide_css_meta.html');
?>
  </head>
<body>
<?php
    include($PUBLIC_DIRECTORY . '/partials/nav.html');
?>
  <div class="container">
    <div class="page-header">
      <h2>People Group Column Descriptions <span class="label label-primary pull-right">Version 1</span></h2>
    </div>
    <div id="table-column-descriptions" class="table-responsive">
        <table  class='table table-hover table-bordered'>
            <tbody>
                <tr>
                    <td>AffinityBloc</td>
                    <td>Affinity Bloc</td>
                </tr>
                <tr>
                    <td>AudioRecordings</td>
                    <td>Gospel audio recordings exist in this language?</td>
                </tr>
                <tr>
                    <td>BibleStatus</td>
                    <td>Bible status</td>
                </tr>
                <tr>
                    <td>BibleYear</td>
                    <td>Year of complete Bible availability</td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>Relationship between people groups and languages. Contact Joshua Project for meaning.</td>
                </tr>
                <tr>
                    <td>Continent</td>
                    <td>Continent</td>
                </tr>
                <tr>
                    <td>CountOfCountries</td>
                    <td>Number of countries of residence</td>
                </tr>
                <tr>
                    <td>CountOfProvinces</td>
                    <td>Number of provinces (states) of residence within this country</td>
                </tr>
                <tr>
                    <td>Country</td>
                    <td>Country name</td>
                </tr>
                <tr>
                    <td>EthnolinguisticMap</td>
                    <td>Ethnolinguistic map URL</td>
                </tr>
                <tr>
                    <td>GospelRadio</td>
                    <td>Gospel radio available in this language?</td>
                </tr>
                <tr>
                    <td>GSEC</td>
                    <td>Global Status of Evangelical Christianity, see http://www.joshuaproject.net/definitions.php</td>
                </tr>
                <tr>
                    <td>Indigenous</td>
                    <td>Is this group indigenous to this country?</td>
                </tr>
                <tr>
                    <td>ISO3</td>
                    <td>International Standards Organization country code</td>
                </tr>
                <tr>
                    <td>JF</td>
                    <td>Does the Jesus film exists in this language?</td>
                </tr>
                <tr>
                    <td>JPScale</td>
                    <td>Joshua Project Progress Scale</td>
                </tr>
                <tr>
                    <td>JPScalePC</td>
                    <td>JPScale, people cluster</td>
                </tr>
                <tr>
                    <td>JPScalePGAC</td>
                    <td>JPScale, People-Group-Across-Countries</td>
                </tr>
                <tr>
                    <td>Language</td>
                    <td>Ethnologue language code, 17th Edition</td>
                </tr>
                <tr>
                    <td>Latitude</td>
                    <td>Latitude value of language polygon or highest density district centroid, for Google maps colored dots</td>
                </tr>
                <tr>
                    <td>LeastReached</td>
                    <td>Are they the Least Reached?  [JPScale < 2.0]</td>
                </tr>
                <tr>
                    <td>LeastReachedBasis</td>
                    <td>Basis for establishing least-reached</td>
                </tr>
                <tr>
                    <td>LeastReachedPC</td>
                    <td>Least Reached, people cluster</td>
                </tr>
                <tr>
                    <td>LeastReachedPGAC</td>
                    <td>Least Reached, People-Group-Across-Countries</td>
                </tr>
                <tr>
                    <td>LocationInCountry</td>
                    <td>Location of people within the country</td>
                </tr>
                <tr>
                    <td>Longitude</td>
                    <td>Longitude value of language polygon or highest density district centroid, for Google maps colored dots</td>
                </tr>
                <tr>
                    <td>LRofTheDayDay</td>
                    <td>Unreached People of the Day day 1-31</td>
                </tr>
                <tr>
                    <td>LRofTheDayMonth</td>
                    <td>Unreached People of the Day month 1-12</td>
                </tr>
                <tr>
                    <td>LRTop100</td>
                    <td>Are they in top 100 of least-reached?</td>
                </tr>
                <tr>
                    <td>LRWebProfile</td>
                    <td>Does an Unreached People of the Day profile exist?</td>
                </tr>
                <tr>
                    <td>NTOnline</td>
                    <td>Does Bible.is have an online NT?</td>
                </tr>
                <tr>
                    <td>NTYear</td>
                    <td>Year of New Testament availability</td>
                </tr>
                <tr>
                    <td>NumberLanguagesSpoken</td>
                    <td>Number of languages spoken by this people group in this country</td>
                </tr>
                <tr>
                    <td>OfficialLang</td>
                    <td>Official language name</td>
                </tr>
                <tr>
                    <td>PCAdherent</td>
                    <td>Percent of people in group who are Christian Adherents</td>
                </tr>
                <tr>
                    <td>PCAdherentPC</td>
                    <td>Percent of people in group who are Christian Adherents in this people cluster</td>
                </tr>
                <tr>
                    <td>PCAdherentPGAC</td>
                    <td>Percent of people in group who are Christian Adherents in all people groups across all countries</td>
                </tr>
                <tr>
                    <td>PCAnglican</td>
                    <td>Percent of people in group who are Anglican</td>
                </tr>
                <tr>
                    <td>PCBuddhist</td>
                    <td>Percent of people in group who are Buddhist</td>
                </tr>
                <tr>
                    <td>PCDoublyProfessing</td>
                    <td>Percent of people in group who are Doubly Professing Christians</td>
                </tr>
                <tr>
                    <td>PCEthnicReligion</td>
                    <td>Percent of people in group who practice Ethnic Religions</td>
                </tr>
                <tr>
                    <td>PCEvangelical</td>
                    <td>Percent of people in group who are Evangelical</td>
                </tr>
                <tr>
                    <td>PCHindu</td>
                    <td>Percent of people in group who are Hindu</td>
                </tr>
                <tr>
                    <td>PCIndependent</td>
                    <td>Percent of people in group who are Independent</td>
                </tr>
                <tr>
                    <td>PCIslam</td>
                    <td>Percent of people in group who are Muslim</td>
                </tr>
                <tr>
                    <td>PCNonReligious</td>
                    <td>Percent of people in group who are  Non-religious</td>
                </tr>
                <tr>
                    <td>PCOrthodox</td>
                    <td>Percent of people in group who are Orthodox</td>
                </tr>
                <tr>
                    <td>PCOtherChristian</td>
                    <td>Percent of people in group who are another form of Christian</td>
                </tr>
                <tr>
                    <td>PCOtherReligion</td>
                    <td>Percent of people in group who practice Other or Smaller Religions</td>
                </tr>
                <tr>
                    <td>PCProtestant</td>
                    <td>Percent of people in group who are Protestant</td>
                </tr>
                <tr>
                    <td>PCRCatholic</td>
                    <td>Percent of people in group who are Roman Catholic</td>
                </tr>
                <tr>
                    <td>PCUnknown</td>
                    <td>Percent of people in group who's religious background is unknown</td>
                </tr>
                <tr>
                    <td>PeopleCluster</td>
                    <td>People cluster</td>
                </tr>
                <tr>
                    <td>PeopleID1</td>
                    <td>Affinity Bloc code</td>
                </tr>
                <tr>
                    <td>PeopleID2</td>
                    <td>People cluster code</td>
                </tr>
                <tr>
                    <td>PeopleID3</td>
                    <td>People-Group-Across-Countries ID number</td>
                </tr>
                <tr>
                    <td>PeopNameAcrossCountries</td>
                    <td>Name of people group across countries of residence</td>
                </tr>
                <tr>
                    <td>PeopNameInCountry</td>
                    <td>Name of people group in this country</td>
                </tr>
                <tr>
                    <td>PCEvangelicalPC</td>
                    <td>Percent of people in group who are Evangelical in this people cluster</td>
                </tr>
                <tr>
                    <td>PCEvangelicalPGAC</td>
                    <td>Percent of people in group who are Evangelical in all people groups across all countries</td>
                </tr>
                <tr>
                    <td>PhotoAddress</td>
                    <td>Photo file name</td>
                </tr>
                <tr>
                    <td>PhotoCopyright</td>
                    <td>Is photo copyrighted?</td>
                </tr>
                <tr>
                    <td>PhotoCreativeCommons</td>
                    <td>Does photo have creative commons licensing?</td>
                </tr>
                <tr>
                    <td>PhotoCredits</td>
                    <td>Photo source, text for credits display</td>
                </tr>
                <tr>
                    <td>PhotoCreditURL</td>
                    <td>Photo source link, hyperlink for credits display</td>
                </tr>
                <tr>
                    <td>PhotoHeight</td>
                    <td>Photo height</td>
                </tr>
                <tr>
                    <td>PhotoPermission</td>
                    <td>Does Joshua Project have permission to use this photo?</td>
                </tr>
                <tr>
                    <td>PhotoWidth</td>
                    <td>Photo width</td>
                </tr>
                <tr>
                    <td>Population</td>
                    <td>Population of people group in the given country</td>
                </tr>
                <tr>
                    <td>PopulationPercentUN</td>
                    <td>Population percent of UN country population</td>
                </tr>
                <tr>
                    <td>PortionsYear</td>
                    <td>Year of scripture portions availability</td>
                </tr>
                <tr>
                    <td>PrimaryLanguageDialect</td>
                    <td>Primary language dialect in this country</td>
                </tr>
                <tr>
                    <td>PrimaryLanguageName</td>
                    <td>Primary language of the people group in this country</td>
                </tr>
                <tr>
                    <td>PrimaryReligion</td>
                    <td>Primary religion of the people group in this country [1 - Christianity, 2 - Buddhism, 4 - Ethnic Religions, 5 - Hinduism, 6 - Islam, 7 - Non-Religious, 8 - Other/Small, 9 - Unknown]</td>
                </tr>
                <tr>
                    <td>PrimaryReligionPC</td>
                    <td>Primary religion of the peole group in this people cluster</td>
                </tr>
                <tr>
                    <td>PrimaryReligionPGAC</td>
                    <td>Primary religion of the people group in all people groups across all countries</td>
                </tr>
                <tr>
                    <td>ProfileTextExists</td>
                    <td>Does profile text exist for this people group?</td>
                </tr>
                <tr>
                    <td>RaceCode</td>
                    <td>Ethnicity code from WCD data</td>
                </tr>
                <tr>
                    <td>RankLocation</td>
                    <td>Priority Ranking location score</td>
                </tr>
                <tr>
                    <td>RankMinistryTools</td>
                    <td>Priority Ranking ministry tools score</td>
                </tr>
                <tr>
                    <td>RankOverall</td>
                    <td>Priority Ranking overall score 0 to 100, 100 most needy</td>
                </tr>
                <tr>
                    <td>RankPopulation</td>
                    <td>Priority Ranking population score</td>
                </tr>
                <tr>
                    <td>RankProgress</td>
                    <td>Priority Ranking progress score</td>
                </tr>
                <tr>
                    <td>Region</td>
                    <td>Primary religion of the people group in this country [1 - Christianity, 2 - Buddhism, 4 - Ethnic Religions, 5 - Hinduism, 6 - Islam, 7 - Non-Religious, 8 - Other/Small, 9 - Unknown]</td>
                </tr>
                <tr>
                    <td>RegionName</td>
                    <td>Region name</td>
                </tr>
                <tr>
                    <td>ReligionSubdivision</td>
                    <td>Subdivision of the primary religion</td>
                </tr>
                <tr>
                    <td>RLG3</td>
                    <td>Primary religion code</td>
                </tr>
                <tr>
                    <td>RLG3PC</td>
                    <td>Primary Religion Code of the peole group in this people cluster</td>
                </tr>
                <tr>
                    <td>RLG3PGAC</td>
                    <td>Primary Religion Code of the people group in all people groups across all countries</td>
                </tr>
                <tr>
                    <td>RLG4</td>
                    <td>Religion subdivision code</td>
                </tr>
                <tr>
                    <td>ROG2</td>
                    <td>Registry of Geographic Places continent code</td>
                </tr>
                <tr>
                    <td>ROG3</td>
                    <td>2 letter ISO country code</td>
                </tr>
                <tr>
                    <td>ROL3</td>
                    <td>Ethnologue language code, 17th Edition</td>
                </tr>
                <tr>
                    <td>ROL3OfficialLanguage</td>
                    <td>Official language code</td>
                </tr>
                <tr>
                    <td>ROL4</td>
                    <td>Dialect code</td>
                </tr>
                <tr>
                    <td>ROP1</td>
                    <td>Registry of Peoples - affinity bloc code</td>
                </tr>
                <tr>
                    <td>ROP2</td>
                    <td>Registry of Peoples - people cluster code</td>
                </tr>
                <tr>
                    <td>ROP3</td>
                    <td>Registry of Peoples - people group code</td>
                </tr>
                <tr>
                    <td>SecurityLevel</td>
                    <td>0=Open, 1=Moderate security concerns, 2= Significant security concerns</td>
                </tr>
                <tr>
                    <td>SpeakNationalLang</td>
                    <td>Does this group speak the national language?</td>
                </tr>
                <tr>
                    <td>StonyGround</td>
                    <td>Is this people group stony ground for evangelism? If "Y" the default for all people groups in this country is "Least-Reached"</td>
                </tr>
                <tr>
                    <td>Top10Ranking</td>
                    <td>Priority Ranking top ten - assigned based on overall ranking and manual process</td>
                </tr>
                <tr>
                    <td>TranslationNeedQuestionable</td>
                    <td>Is the translation questionable?</td>
                </tr>
                <tr>
                    <td>Unengaged</td>
                    <td>Is this Ppeople group unengaged?</td>
                </tr>
                <tr>
                    <td>UNMap</td>
                    <td>URL for UN map</td>
                </tr>
                <tr>
                    <td>Window1040</td>
                    <td>Does this people group live in the 1040 Window?</td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
<?php
    include($PUBLIC_DIRECTORY . '/partials/footer.html');
    include($PUBLIC_DIRECTORY . '/partials/site_wide_footer_js.html');
?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('li.documentation-nav, li.people-groups-column-desc-nav').addClass('active');
            });
        </script>
</body>
</html>