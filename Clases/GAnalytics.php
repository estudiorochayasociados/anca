<?php namespace Clases;

/**
 * @author ikhuerta@gmail.com more info at blog.ikhuerta.com
 * @name Google Analytis PHP Class
 * @version 1.1
 *
 *
 * Simple Example:
 *

$GAnalytics = new GAnalytics("myemail@gmail.com","mypassword12345");

// By default it connects to your first account and takes last 30 days
$pageViews =    $GAnalytics->getMostViewedPages();
$landingPages =    $GAnalytics->getLandingPages();
$keywords =        $GAnalytics->getKeywords();

 *
 * Advanced Example:
 *

$GAnalytics = new GAnalytics("myemail@gmail.com","mypassword12345");

$GAnalytics->setAccount( 123456789 ); // the id of the accont
$GAnalytics->setDates( "2009-05-27", "2009-05-28" );

// geting pageviews by browser order by pageviews descendant...
$pageViews =$GAnalytics->requestValues(
"ga:browser", // dimension
"ga:pageviews", // metric
"-ga:pageviews" // sortBy
);

 *
 * Example With filters
 *

$GAnalytics = new GAnalytics("myemail@gmail.com","mypassword12345");
$GAnalytics->setAccount( 123456789 ); // the id of the accont
$GAnalytics->setDates( "2009-05-27", "2009-05-28" );

// Creating a filter: keywords with the string 'seo' OR the string 'jquery'
$filter = $GAnalytics->newFilter();
$filter->addANDFilter( "ga:keyword", "=@", "seo" );
$filter->addORFilter( "ga:keyword", "=@", "jquery" );

// Viewing the results
$pageViews = $GAnalytics->requestValues( "ga:keyword", "ga:pageviews", "-ga:pageviews" , $filter );

 *
 * More information about dimensions and metrics at http://code.google.com/intl/es-ES/apis/analytics/docs/gdata/gdataReferenceDimensionsMetrics.html
 * More information about filters at http://code.google.com/intl/es-ES/apis/analytics/docs/gdata/gdataReference.html#filtering
 */
class GAnalytics
{

    private $loginData = array();

    public $accounts        = false; // Array de webs de la cuenta de google Analytics actual
    public $selectedAccount = false; // Cuenta seleccionada para ver los informes

    private $dateStart;
    private $dateEnd;

    const APPNAME        = "Google-Analytics-PHP-Class@ikhuerta.com";
    const ACCOUNTFEEDURL = "http://www.google.com/analytics/feeds/accounts/ga:";

    public function __Construct($email, $pass)
    {
        $this->email = $email;
        $this->pass  = $pass;
        // By default it's take the 30 last days
        $this->dateStart = date("Y-m-d", mktime(0, 0, 1) - 60 * 60 * 24 * 31); // 31 days Ago
        $this->dateEnd   = date("Y-m-d", mktime(0, 0, 1) - 60 * 60 * 24); // yesterday
        $this->login();
    }

    protected function login()
    {
        $loginUrl = 'https://www.google.com/accounts/ClientLogin?accountType=GOOGLE&Email=' . $this->email . '&Passwd=' . $this->pass . '&source=' . self::APPNAME . '&service=analytics';
        $data     = file_get_contents($loginUrl);
        $data     = explode("\n", $data);

        foreach ($data as $d) {
            $d                      = explode("=", $d);
            $this->loginData[$d[0]] = $d[1];
        }
    }

    public function getAccounts()
    {
        if (!$this->accounts) {
            $this->sendAuthHeaders();
            $data = file_get_contents('https://www.google.com/analytics/feeds/accounts/default?start-index=1&max-results=99');
            $data = simplexml_load_string($data);

            $data = $data->entry;
            for ($i = 0; $i < count($data); $i++) {
                $temp         = explode("ga:", $data[$i]->id);
                $data[$i]->id = $temp[1];
            }
            $this->accounts = $data;
        }
        return $this->accounts;
    }

    public function setAccount($accountId)
    {
        if (!is_numeric($accountId)) {
            // You can replace this for a exception...
            echo "Error: Invalid AccountId";
            return false;
        }

        if (!$this->accounts) {
            $this->getAccounts();
        }

        $this->selectedAccount = false;
        if (isset($this->accounts[$accountId])) {
            $this->selectedAccount = $this->accounts[$accountId];
        } else {
            foreach ($this->accounts as $a) {
                if ($accountId == $a->id) {
                    $this->selectedAccount = $a;
                }
            }
        }

        if ($this->selectedAccount) {
            return true;
        } else {
            echo "Error: AccountId $accountId not found for your user";
            return false;
        }
    }

    protected function sendAuthHeaders()
    {
        ini_set('user_agent', self::APPNAME . "\r\nAuthorization: GoogleLogin auth=" . $this->loginData["Auth"]);
    }

    public function setDates($start, $end)
    {
        $this->dateStart = $start;
        $this->dateEnd   = $end;
    }

    public function requestXML($dimensions, $metrics, $sort, $filtersObject = '', $start = 1, $numberOfResults = 10000)
    {
        if (!$this->selectedAccount) {
            $this->setAccount(0); // If not selected, select first.
        }

        $this->sendAuthHeaders();

        if (is_object($filtersObject)) {
            $filters = $filtersObject->getFilterString();
        } else {
            $filters = '';
        }

        $data    = file_get_contents('https://www.google.com/analytics/feeds/data?ids=ga:' . $this->selectedAccount->id . '&dimensions=' . $dimensions . '&metrics=' . $metrics . '&sort=' . $sort . '&start-index=' . $start . '&max-results=' . $numberOfResults . $filters . '&start-date=' . $this->dateStart . '&end-date=' . $this->dateEnd . '');
        $data    = str_replace(array('dxp:dimension', 'dxp:metric'), array('dimension', 'metric'), $data);
        $xmlData = simplexml_load_string($data);
        return $xmlData;
    }

    public function requestValues($dimensions, $metrics, $sort, $filtersObject = '', $start = 1, $numberOfResults = 10000)
    {
        $xmlData = $this->requestXML($dimensions, $metrics, $sort, $filtersObject, $start, $numberOfResults);
        if (count($xmlData->entry) > 0) {

            foreach ($xmlData->entry as $x) {

                if (count($x->dimension) <= 1) {
                    $dimArray = "" . $x->dimension[0]['value'];
                } else {
                    $dimArray = array();
                    foreach ($x->dimension as $dim) {
                        $dimArray[str_replace('ga:', '', $dim['name'])] = "" . $dim['value'];
                    }
                }

                if (count($x->metric) <= 1) {
                    $metArray = "" . $x->metric[0]['value'];
                } else {
                    $metArray = array();
                    foreach ($x->metric as $met) {
                        $metArray[str_replace('ga:', '', $met['name'])] = "" . $met['value'];
                    }
                }

                $array[] = array(
                    "element" => $dimArray,
                    "value"   => $metArray,
                );
            }
        } else {
            $array = array();
        }
        return $array;
    }

    public function newFilter()
    {
        return new GAnalytics_Filter;
    }

    public function getMostViewedPages($start = 1, $numberOfResults = 10000)
    {
        return $this->requestValues("ga:pagePath", "ga:pageviews", "-ga:pageviews", '', $start, $numberOfResults);
    }
    public function getLandingPages($start = 1, $numberOfResults = 10000)
    {
        return $this->requestValues("ga:pagePath", "ga:entrances", "-ga:entrances", '', $start, $numberOfResults);
    }
    public function getKeywords($start = 1, $numberOfResults = 10000)
    {
        return $this->requestValues("ga:keyword", "ga:entrances", "-ga:entrances", '', $start, $numberOfResults);
    }

}

/**
 * Class to create filters.
 *
 * You can see more information about filters, operators and expressions at:
 * http://code.google.com/intl/es-ES/apis/analytics/docs/gdata/gdataReference.html#filtering
 */
class GAnalytics_Filter
{
    public $filtersArray = array();

    public function addANDFilter($dimensionOrMetricName, $filterOperator, $expression)
    {
        $this->filtersArray[] = array(
            "separator" => ";",
            "text"      => $dimensionOrMetricName . rawurlencode($filterOperator) . $expression,
        );
    }

    public function addORFilter($dimensionOrMetricName, $filterOperator, $expression)
    {
        $this->filtersArray[] = array(
            "separator" => ",",
            "text"      => $dimensionOrMetricName . rawurlencode($filterOperator) . $expression,
        );
    }

    public function getFilterString()
    {
        $string = "";
        if (count($this->filtersArray) > 0) {
            $string = "&filters=";
            $first  = true;
            foreach ($this->filtersArray as $f) {
                if (!$first) {
                    $string .= $f['separator'];
                }
                $string .= $f['text'];
                $first = false;
            }
        }
        return $string;
    }

}
