<?php namespace Michiel\RestfulApiContext\Traits;

use Exception;

/**
 * Helpers trait
 *
 * Helper methods
 */
trait Helpers {

    /**
    * Get parameter
    * Get context parameter setup via behat.yml
    *
    * @param string $name name of parameter
    */
    public function getParameter($name)
    {
        return (isset($this->parameters[$name])) ? $this->parameters[$name] : null;
    }

    /**
    * Check Property
    *
    * Retrieve a property from returned JSON data
    *
    * @param object $data decoded json object returned by request
    * @param string name of property to retrieve. Uses 'dot-notation' to indicate a property down the object tree
    * @param boolean to indicate if we expect the property to exist or not
    */
    public function checkProperty($data, $propertyName, $shouldExist = true)
    {

        // does the propertyName have dots? Then search the object tree
        if(strpos($propertyName, '.') !== false)
        {
            $propertyArray = explode('.', $propertyName);

            $property = $data;

            foreach($propertyArray as $value)
            {

                // is array notation ([0]) used
                $arrayNotation = preg_match('^\[(.*?)\]^', $value, $matches);

                if($arrayNotation)
                {
                    // grab the array key
                    $key = $matches[0][1];
                    // remove the brackets from value
                    $value = str_replace('['.$key.']', '', $value);
                    // make a temp copy of the array
                    $array = $property->$value;

                    if (!isset($array[$key]))
                    {
                        if($shouldExist)
                        {
                            $this->throwError('Property "'.$propertyName.'" is not set!');
                        }
                    }
                    else
                    {
                        if(!$shouldExist)
                        {
                            $this->throwError('Property "'.$propertyName.'" is set!');
                        }

                        $property = $array[$key];
                    }

                }
                else
                {
                    if (!isset($property->$value))
                    {
                        if($shouldExist)
                        {
                            $this->throwError('Property "'.$propertyName.'" is not set!');
                        }
                    }
                    else
                    {

                        // the property should not exist and the value should equal the last item in the property array
                        if(!$shouldExist && $value == $propertyArray[count($propertyArray) - 1])
                        {
                            $this->throwError('Property "'.$propertyName.'" is set!');
                        }
                        $property = $property->$value;
                    }
                }

            }

            return $property;
        }

        else
        {
            if (!isset($data->$propertyName))
            {

                if($shouldExist)
                {
                    $this->throwError('Property "'.$propertyName.'" is not set!');
                }
            }
            else
            {

                if(!$shouldExist)
                {
                    $this->throwError('Property "'.$propertyName.'" is set!');
                }

                $property = $data->$propertyName;
                if(!is_array($property) && !is_object($property))
                {
                    return (string) $property;
                }
                else
                {
                    return $property;
                }

            }
        }
    }

    /**
     * Compare
     *
     * Compare 2 values with an operator
     * @param  string $property1
     * @param  string $operator
     * @param  string $property2
     * @return boolean
     */
    public function compare($property1, $operator, $property2)
    {
        switch ($operator)
        {
            case "=":
                return $property1 == $property2;
            break;

            case "!=":
                return $property1 != $property2;
            break;

            case ">=":
                return $property1 >= $property2;
            break;

            case "<=":
                return $property1 <= $property2;
            break;

            case ">":
                return $property1 >  $property2;
            break;

            case "<":
                return $property1 <  $property2;
            break;

            default:
                return true;
            break;
        }
    }


    public function throwError($message, $data = false)
    {

        throw new Exception(
            $message."\n\n" .
            'Url: '.$this->url .
            'Response:'."\n=======================\n".print_r($this->data, true)
        );
    }

}
