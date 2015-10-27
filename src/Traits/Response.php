<?php namespace Michiel\RestfulApiContext\Traits;

trait Response {

    /**
    * @Then /^the response is JSON$/
    */
    public function theResponseIsJson()
    {

        if (empty($this->data))
        {
            $this->throwError('Response is not json');
        }
    }

    /**
    * @Then /^the content type of the response is "([^"]*)"$/
    */
    public function theContentTypeOfTheResponseIs($expectedContentType)
    {
        $realContentType = $this->response->getHeader('Content-Type');

        if($realContentType != $expectedContentType)
        {
            $this->throwError('Content-type of response is not '.$expectedContentType.', but '.$realContentType);
        }
    }

    /**
    * @Then /^the response status code is (\d+)$/
    */
    public function theResponseStatusCodeIs($expectedStatusCode)
    {
        $realStatusCode = (string) $this->response->getStatusCode();

        if ($realStatusCode !== $expectedStatusCode)
        {
            $this->throwError('Statuscode of response is not '.$expectedStatusCode.', but '.$realStatusCode);
        }
    }


    /**
    * @Given /^the response has a "([^"]*)" property$/
    */
    public function theResponseHasAProperty($propertyName)
    {

        // Note: the checkProperty method takes care of throwing an exception if property not found
        $this->checkProperty($this->data, $propertyName);

    }

    /**
     * @Given /^the response does not have a "([^"]*)" property$/
     * @Given /^the response doesn't have a "([^"]*)" property$/
     */
    public function theResponseDoesNotHaveAProperty($propertyName)
    {

        $this->checkProperty($this->data, $propertyName, false);

    }

    /**
    * @Then /^the "([^"]*)" property equals "([^"]*)"$/
    */
    public function thePropertyEquals($propertyName, $propertyValue)
    {

        $property = $this->checkProperty($this->data, $propertyName);

        if(!preg_match("/$propertyValue/", (string)$property))
        {
            $this->throwError('The property "'.$propertyName.'" does not equal "'.$propertyValue.'", but equals "'.$property.'"');
        }

    }


    /**
    * @Then /^the "([^"]*)" property does not equal "([^"]*)"$/
    */
    public function thePropertyDoesNotEqual($propertyName, $propertyValue)
    {

        $property = $this->checkProperty($this->data, $propertyName);

        // throw an error when values are equal
        if($propertyValue == (string)$property)
        {
            $this->throwError('The property "'.$propertyName.'" equals "'.$propertyValue.'", but it is expected to be different');
        }

    }

    /**
     * @Then /^the type of the "([^"]*)" property is "([^"]*)"$/
     */
    public function theTypeOfThePropertyIs($propertyName, $typeString)
    {

        $property = $this->checkProperty($this->data, $propertyName);

        $pass = true;
        switch (strtolower($typeString))
        {
            case 'numeric':
                if (!is_numeric($property))
                {
                    $pass = false;
                }
            break;

            case 'object':
                if (!is_object($property))
                {
                    $pass = false;
                }
            break;

            case 'array':
                if (!is_array($property)) {
                    $pass = false;
                }
            break;

            case 'boolean':
                if (!is_bool($property)) {
                    $pass = false;
                }
            break;

            case 'string':
                if (!is_string($property)) {
                    $pass = false;
                }
            break;

            case 'numeric or boolean':
                if (!is_numeric($property) && !is_bool($property))
                {
                    $pass = false;
                }
            break;

        }

        if($pass === false)
        {
            $this->throwError('Property "'.$propertyName.'" is not of the correct type "'.strtolower($typeString).'"');
        }

    }

    /**
    * @Then /^the "([^"]*)" property "([^"]*)" the "([^"]*)" property$/
    */
    public function thePropertyTheProperty($propertyName1, $operator, $propertyName2)
    {

        $property1 = $this->checkProperty($this->data, $propertyName1);
        $property2 = $this->checkProperty($this->data, $propertyName2);

        if(!$this->compare($property1, $operator, $property2))
        {
            $this->throwError('Property "'.$propertyName1.'" is not "'.$operator.'" property "'.$propertyName2.'"');
        }

    }

    /**
    * @Given /^the "([^"]*)" property (starts|ends) with "([^"]*)"$/
    */
    public function thePropertyStartsWith($propertyName, $verb, $characters)
    {

        $property = $this->checkProperty($this->data, $propertyName);

        // compare characters with substr on property value
        if($characters != substr($property, $verb == 'starts' ? 0 : -1, strlen($characters)))
        {
            $this->throwError('Property "'.$propertyName.'" does not '.rtrim($verb, 's').' with "'.$characters.'"');
        }

    }

    /**
     * @Given /^the "([^"]*)" property contains "([^"]*)"$/
     */
    public function thePropertyContains($propertyName, $string)
    {

        $property = $this->checkProperty($this->data, $propertyName);

        if(stripos($property, $string) === false)
        {
            $this->throwError('Property "'.$propertyName.'" does not contain "'.$string.'"');
        }

    }

    /**
     * @Given /^the length of the "([^"]*)" property equals "([^"]*)"$/
     */
    public function theLengthOfThePropertyEquals($propertyName, $expectedLength)
    {

        $property = $this->checkProperty($this->data, $propertyName);

        $realLength = sizeof($property);
        if($realLength != $expectedLength)
        {
            $this->throwError('The length of "'.$propertyName.'" is not '.$expectedLength.', but '.$realLength);
        }
    }

}
