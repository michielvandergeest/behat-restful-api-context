<?php namespace Michiel\RestfulApiContext;

use stdClass;
use Behat\Behat\Context\Context;
use GuzzleHttp\Client;

use Michiel\RestfulApiContext\Traits\Request;
use Michiel\RestfulApiContext\Traits\Response;
use Michiel\RestfulApiContext\Traits\Helpers;
use Michiel\RestfulApiContext\Traits\Authentication;
use Michiel\RestfulApiContext\Traits\Errors;
use Michiel\RestfulApiContext\Traits\Memory;

class RestfulApiContext implements Context {

    use Request;
    use Response;
    use Helpers;
    use Authentication;
    use Errors;
    use Memory;

    private $entity = null;
    private $input = null;
    private $method = 'GET';
    private $response = null;
    private $url = null;
    private $parameters = [
        'base_url' => 'http://localhost/',
        'login_path' => 'public/login',
        'login_identifier' => 'email'
    ];
    private $memory = [];

    /**
    * Initializes context.
    *
    * @param array $parameters context parameters
    */
    public function __construct($parameters = [])
    {
        $this->input = new stdClass();
        $this->client = new Client();
        $this->parameters = array_merge($this->parameters, $parameters);
    }

}
