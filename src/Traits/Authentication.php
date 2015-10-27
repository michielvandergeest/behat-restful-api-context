<?php namespace Michiel\RestfulApiContext\Traits;

trait Authentication {

    /**
     * @Given /^that I am logged in as "([^"]*)" with password "([^"]*)"$/
     */
    public function thatIAmLoggedInAsWithPassword($username, $password)
    {
        $url = $this->getParameter('base_url') . $this->getParameter('login_path');

        $response = $this->client->post($url, ['body'=>[$this->getParameter('login_identifier') => $username, 'password' => $password]]);

        $data = json_decode($response->getBody(true));

        $token = false;
        if(isset($data->data->user->token)) $token = $data->data->user->token;
        if(isset($data->data->token)) $token = $data->data->token;
        if($token)
        {
            $this->client->setDefaultOption('headers/X-Auth-Token', $token);
        }
        else
        {
            $this->throwException('Failed to log in with "'.$username.'" and "'.$password.'"');
        }
    }

}
