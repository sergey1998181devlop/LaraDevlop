<?php
namespace App\Helpers\NewsApiHelper;


/**
 * Standalone News API implementation
 */
class NewsApi {

    /**
     * News API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Endpoint to call
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Array of properties to build url
     *
     * @var array
     */
    protected $properties;

    /**
     * Base url
     *
     * @var string
     */
    protected $baseUrl = 'https://newsapi.org/v2/';

    /**
     * Url
     *
     * @var string
     */
    protected $url;

    /**
     * Fetched articles
     *
     * @var array
     */
    public $articles = [];

    /**
     * Fetched sources
     *
     * @var array
     */
    public $sources = [];

    /**
     * Number of fetched articles
     *
     * @var integer
     */
    public $nArticles = 0;

    /**
     * Number of fetched sources
     *
     * @var integer
     */
    public $nSources = 0;

    /**
     * fetched articles + fetched sources
     *
     * @var integer
     */
    public $nResults = 0;

    /**
     * Number of results available
     *
     * @var int
     */
    public $totalResults;

    /**
     * Errors are stored here
     *
     * @var array
     */
    public $errors = [];

    /**
     * List of avaliable countries
     *
     * @var array
     */
    protected $allowedCountries = [
        'ae', 'ar', 'at', 'au', 'be', 'bg', 'br', 'ca', 'ch', 'cn', 'co',
        'cu', 'cz', 'de', 'eg', 'fr', 'gb', 'gr', 'hk', 'hu', 'id', 'ie',
        'il', 'in', 'it', 'jp', 'kr', 'lt', 'lv', 'ma', 'mx', 'my', 'ng',
        'nl', 'no', 'nz', 'ph', 'pl', 'pt', 'ro', 'rs', 'ru', 'sa', 'se',
        'sg', 'si', 'sk', 'th', 'tr', 'tw', 'ua', 'us', 've', 'za'
    ];

    /**
     * List of avaliable categories
     *
     * @var array
     */
    protected $allowedCategories = [
        'business', 'entertainment', 'general', 'health', 'science', 'sports', 'technology'
    ];

    /**
     * List of avaliable languages
     *
     * @var array
     */
    protected $allowedLanguages = [
        'ar', 'de', 'en', 'es', 'fr', 'he', 'it', 'nl', 'no', 'pt', 'ru', 'se', 'ud', 'zh'
    ];

    /**
     * Allowed sortings for endpoint "everything"
     *
     * @var array
     */
    protected $allowedSorting = [
        'relevancy', 'popularity', 'publishedAt'
    ];

    /**
     * Constructor
     *
     * @param string $apiKey
     */
    public function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

    /**
     * Sets up name and allowed properties for "top-headlines" endpoint
     *
     * @param array $params (optional) array of URL parameters $urlParam => $value
     * @return Newsapi
     */
    public function headlines(array $params = []) {
        $this->endpoint = 'top-headlines';

        // allowed properties for this endpoint
        $this->properties = [
            'sources' => null,
            'q' => null,
            'pageSize' => null,
            'page' => null,
            'country' => null,
            'category' => null
        ];

        // sets users properties if passed
        $this->setProperites($params);

        return $this;
    }

    /**
     * Syntactic sugar for headlines
     *
     * @param array $properties
     * @return Newsapi
     */
    public function topHeadlines(array $properties = []) {
        return $this->headlines($properties);
    }

    /**
     * Sets up allowed properties for "everything" endpoint
     *
     * @param array $params (optional) array of URL parameters $urlParam => $value
     * @return Newsapi
     */
    public function everything(array $properties = []) {

        $this->endpoint = 'everything';

        $this->properties = [
            'sources' => null,
            'q' => null,
            'pageSize' => null,
            'page' => null,
            'qInTitle' => null,
            'domains' => null,
            'excludeDomains' => null,
            'from' => null,
            'to' => null,
            'language' => null,
            'sortBy' => null
        ];

        $this->setProperites($properties);

        return $this;
    }

    /**
     * Sets up allowed properties for "sources" endpoint
     *
     * @param array $params (optional) array of URL parameters $urlParam => $value
     * @return Newsapi
     */
    public function sources (array $properties = []) {

        $this->endpoint = 'sources';

        $this->properties = [
            'category' => null,
            'language' => null,
            'country' => null,
        ];

        $this->setProperites($properties);

        return $this;
    }

    /**
     * Mass assign properties
     *
     * @param array $properties
     * @return void
     */
    public function setProperites(array $properties) {
        foreach($properties as $property => $value) {
            $method = 'set' . ucfirst($property);
            $this->$method($value);
        }
    }

    /**
     * Sets properties and checks for filter methods of the same name
     *
     * @param string $property
     * @param mixed $value
     * @return Newsapi
     * @throws Exception if $property is not in $properies or if filter method returns false
     */
    public function __set(string $property, $value) {

        // check if property is in the array of allowed properties
        if (!array_key_exists($property, $this->properties)) {
            throw new \Exception('Invalid property: ' . $property);
        }

        // check if filter method exists and run it
        if (method_exists($this, $property)) {
            if(!$this->{$property}($value)) {
                throw new \Exception("Invalid value for {$property}: {$value}");
            }
        }

        $this->properties[$property] = $value;

        return $this;
    }

    /**
     * @param string $property
     * @return Newsapi
     * @throws Exception on invalid property
     */
    public function __get(string $property) {
        if (array_key_exists($property, $this->properties)) {
            return $this->properties[$property];
        }

        throw new \Exception('Invalid property: '. $property);
    }

    /**
     * Sets up setters and getters for allowed properties
     *
     * @param string $method
     * @param array $params
     * @return Newsapi
     * @throws Exception if invalid method is called
     */
    public function __call(string $method, array $params) {

        $property = lcfirst(substr($method, 3));

        if (strncasecmp($method, "get", 3) === 0) {
            return $this->$property;
        }
        elseif (strncasecmp($method, "set", 3) === 0) {
            $this->$property = $params[0];
        }
        else {
            throw new \Exception('Invalid method: ' . $method);
        }

        return $this;
    }

    /**
     * Filter function for property country
     *
     * @param string $value
     * @return boolean true if country is in the list of allowed countries
     */
    protected function country(string $value) {
        $this->properties['sources'] = null;
        return in_array($value, $this->allowedCountries);
    }

    /**
     * Filter function for property category
     *
     * @param string $value
     * @return boolean true if category is in the list of allowed categories
     */
    protected function category(string $value) {
        $this->properties['sources'] = null;
        return in_array($value, $this->allowedCategories);
    }

    /**
     * Filter function for property q
     * urlencodes the search term
     *
     * @param string $value
     * @return boolean true
     */
    protected function q(string $value) {
        $this->properties['q'] = urlencode($this->properties['q']);
        return true;
    }

    /**
     * Filter function for property qInTitle
     * urlencodes the search term
     *
     * @param string $value
     * @return boolean true
     */
    protected function qInTitle($value) {
        $this->properties['qInTitle'] = urlencode($this->properties['qInTitle']);
        return true;
    }

    /**
     * Filter function for property language
     *
     * @param string $value
     * @return boolean true if language is in the list of allowed laguages
     */
    protected function language($value) {
        return in_array($value, $this->allowedLanguages);
    }

    /**
     * Filter function for property sortBy
     *
     * @param string $value
     * @return boolean true if sorting is in the list of allowed sortings
     */
    protected function sortBy($value) {
        return in_array($value, $this->allowedSorting);
    }

    /**
     * Filter function for property pageSize
     *
     * @param string $value
     * @return boolean true if page size is beetwen 1 and 100 (max number of results per call)
     */
    protected function pageSize($value) {
        return $value >= 1 && $value <= 100;
    }

    /**
     * Creates url from properties for an api call
     *
     * @return Newsapi
     */
    protected function makeUrl() {
        $this->url = "{$this->baseUrl}{$this->endpoint}?apiKey={$this->apiKey}";

        foreach($this->properties as $property => $value) {
            if ($value === null) {
                continue;
            }

            $this->url .= "&{$property}={$value}";
        }

        return $this;
    }

    /**
     * Inits curl, makes the api call, decodes returned json
     *
     * @return array $result result from api call
     */
    protected function makeCurl() {
        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, $this->url);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
        $result = json_decode(curl_exec($request), true);
        curl_close($request);

        return $result;
    }

    /**
     * Handles the result
     *
     * @param array $result
     * @return Newsapi
     */
    protected function handleResults(array $result) {
        if ($this->endpoint === 'sources') {
            return $this->handleSources($result);
        }

        return $this->handleArticles($result);

    }

    /**
     * Handles the article results (top-headlines & everything)
     *
     * @param array $result
     * @return Newsapi
     */
    protected function handleArticles(array $result) {
        $this->articles = array_merge($this->articles, $result['articles']);
        $this->totalResults = $result['totalResults'];
        $nOfArticles = count($result['articles']);
        $this->nArticles += $nOfArticles;
        $this->nResults += $nOfArticles;

        return $this;
    }

    /**
     * Handles the sources results
     *
     * @param array $result
     * @return Newsapi
     */
    protected function handleSources(array $result) {
        $this->sources = array_merge($result['sources'], $this->sources);
        $this->totalResults = count($result['sources']);
        $this->nSources += $this->totalResults;
        $this->nResults += $this->totalResults;

        return $this;
    }

    /**
     * Method that brings it all together
     *
     * @return Newsapi
     */
    public function get() {

        $result = $this->makeUrl()->makeCurl();

        // if there are errors add them to errors array
        if ($result['status'] === 'error') {
            $this->errors[$result['code']] = $result['message'];
        }
        else {
            $this->handleResults($result);
        }

        return $this;
    }


}
