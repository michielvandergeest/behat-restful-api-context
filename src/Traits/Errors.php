<?php namespace Michiel\RestfulApiContext\Traits;

/**
 * Errors trait
 *
 * Check if errors are returned by the API
 */
trait Errors {

    /**
     * @Given /^has an error with code "([^"]*)", field "([^"]*)" and message "([^"]*)"$/
     */
    public function hasAnErrorWithCodeFieldAndMessage($code, $field, $message)
    {

        $errors = $this->checkProperty($this->data, 'errors');

        // assume the error is not found
        $found = false;
        foreach($errors as $error)
        {
            $error = (array)$error;
            // check if code, field and message are available
            if($error['code'] == $code && $error['field'] == $field && $error['message'] == $message)
            {
                $found = true;
            }
        }

        // throw an error if we didn't find the error
        if(!$found)
        {
            $this->throwError('Error code and / or error message not found!');
        }

    }

}
