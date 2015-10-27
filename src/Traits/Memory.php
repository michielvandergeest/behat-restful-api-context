<?php namespace Michiel\RestfulApiContext\Traits;

/**
 * Memory trait
 *
 * Provides functions to "remember" values that the API returns and retrieve them
 */
trait Memory {

    /**
    * @Given /^that its "([^"]*)" is "([^"]*)" (from memory)$/
    */
    public function thatTheItsIs($propertyName, $propertyValue)
    {
        $this->input->$propertyName = $this->memory[$propertyValue];
    }

    /**
     * @Then /^I remember the "([^"]*)" property as "([^"]*)"$/
     */
    public function iRememberThePropertyAs($propertyName, $variableName)
    {
        $this->memory[$variableName] = $this->getProperty($this->data, $propertyName);
    }

}
