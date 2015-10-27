<?php namespace Michiel\RestfulApiContext\Traits;

use GuzzleHttp\Post\PostFile;

trait Request {

    /**
    * @Given /^that I want to get a "([^"]*)"$/
    * @Given /^that I want to get an "([^"]*)"$/
    * @Given /^that I want to find a "([^"]*)"$/
    * @Given /^that I want to find an "([^"]*)"$/
    */
    public function thatIWantToGetA($entity)
    {
        $this->entity = $entity;
        $this->method = 'get';
    }

    /**
    * @Given /^that I want to create a new "([^"]*)"$/
    * @Given /^that I want to create a "([^"]*)"$/
    * @Given /^that I want to create an "([^"]*)"$/
    * @Given /^that I want to make a new "([^"]*)"$/
    * @Given /^that I want to make a "([^"]*)"$/
    * @Given /^that I want to make an "([^"]*)"$/
    */
    public function thatIWantToCreateA($entity)
    {
        $this->entity = $entity;
        $this->method = 'post';
    }

    /**
    * @Given /^that I want to update a "([^"]*)"$/
    * @Given /^that I want to update an "([^"]*)"$/
    * @Given /^that I want to change a "([^"]*)"$/
    * @Given /^that I want to change an "([^"]*)"$/
    */
    public function thatIWantToUpdateA($entity)
    {
        $this->entity = $entity;
        $this->method = 'put';
    }

    /**
    * @Given /^that I want to delete a "([^"]*)"$/
    * @Given /^that I want to delete an "([^"]*)"$/
    */
    public function thatIWantToDeleteA($entity)
    {
        $this->entity = $entity;
        $this->method = 'delete';
    }

    /**
    * @Given /^that its "([^"]*)" is "([^"]*)"$/
    */
    public function thatItsIs($propertyName, $propertyValue)
    {
        $this->input->$propertyName = $propertyValue;
    }

    /**
    * @Given /^that I upload a file "([^"]*)" as "([^"]*)"$/
    */
    public function thatIUploadAs($file, $propertyName)
    {
        if(!preg_match("/(?:ht|f)tps?:\/\//", $file))
        {
            $file = $this->getParameter('assets_path').$file;
        }
        $this->input->$propertyName = new PostFile($propertyName, fopen($file, 'r'));
    }

    /**
    * @When /^I request "([^"]*)"$/
    */
    public function iRequest($pageUrl)
    {
        $baseUrl = $this->getParameter('base_url');
        $this->url = $baseUrl.$pageUrl;

        switch (strtoupper($this->method))
        {

            case 'GET':
                $this->response = $this->client->get($this->url.'?'.http_build_query((array) $this->input));
            break;

            case 'POST':
                $this->response = $this->client->post($this->url, ['body' => (array) $this->input]);
            break;

            case 'PUT':
                $this->response = $this->client->put($this->url, ['body' => (array) $this->input]);
            break;

            case 'DELETE':
                $this->response = $this->client->delete($this->url.'?'.http_build_query((array) $this->input));
            break;

        }

        $this->data = json_decode($this->response->getBody(true));
    }

}
