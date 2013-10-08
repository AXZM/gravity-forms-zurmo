<?php //namespace Zurmo;
include('APIRestHelper.php');

class ZurmoAPI
{   
    /*
    |-----------------------------------------------------
    | The URL, username, and password of the Zurmo Install
    |------------------------------------------------------
    */
        public $url;
        public $username;
        public $password;

    /*
    |------------------------------------
    | Construct Function
    |------------------------------------
    */
        public function setup($url, $username, $password)
        {   
            /*
            |-------------------------------------
            | Define the url of the Zurmo Install
            |-------------------------------------
            */
                $this->url = $url;
            /*
            |-------------------------------------
            | Define the username for API access
            |-------------------------------------
            */
                $this->username = $username;

            /*
            |-------------------------------------
            | Define the password for API Access
            |-------------------------------------
            */
                $this->password = $password;
        }

    /*
    |------------------------------------
    | Login Function
    |------------------------------------
    |   
    | Authenticates a user for the API
    |
    */
        public function login()
        {
            $headers = array(
                'Accept: application/json',
                'ZURMO_AUTH_USERNAME: ' . $this->username,
                'ZURMO_AUTH_PASSWORD: ' . $this->password,
                'ZURMO_API_REQUEST_TYPE: REST',
            );
            $response = ApiRestHelper::createApiCall($this->url.'/app/index.php/zurmo/api/login', 'POST', $headers);
            
            $response = json_decode($response, true);

            if ($response['status'] == 'SUCCESS')
            {
                return $response['data'];
            }
            else
            {
                return $response;
            }
        }

    /*
    |------------------------------------
    | New Lead Function
    |------------------------------------
    |   
    | Creates a new lead in the system
    |
    */  
        public function newLead($data)
        {
            /*
            |-------------------------------------
            | Login to API
            |-------------------------------------
            */
               $authenticationData = $this->login();
              
                //Add code to check if user is logged successfully
               if(!array_key_exists('sessionId', $authenticationData))
               {
                    return var_dump($authenticationData);
                    
               }
               
            /*
            |-------------------------------------
            | Set headers
            |-------------------------------------
            */
                $headers = array(
                    'Accept: application/json',
                    'ZURMO_SESSION_ID: ' . $authenticationData['sessionId'],
                    'ZURMO_TOKEN: ' . $authenticationData['token'],
                    'ZURMO_API_REQUEST_TYPE: REST',
                );
            
            /*
            |-------------------------------------
            | Make API call
            |-------------------------------------
            */
                $response = ApiRestHelper::createApiCall($this->url.'/app/index.php/leads/contact/api/create/', 'POST', $headers, array('data' => $data));
                return var_dump($response);
                // $response = json_decode($response, true);

            /*
            |-------------------------
            | Handle Response
            |-------------------------
            */    
                if ($response['status'] == 'SUCCESS')
                {
                    $contact = $response['data'];
                    return $contact;
                    //Do something with contact data
                }
                else
                {
                    // Error
                    $errors = $response['errors'];
                    return $errors;
                    // Do something with errors, show them to user
                }
        }

        /*
        |---------------------------------------
        | Contact Attributes
        |---------------------------------------
        |   
        | List all the attributes for a particular contact
        |
        */
        public function contactAttributes($id)
        {
            $authenticationData = $this->login();
            //Add code to check if user is logged successfully

            $headers = array(
                'Accept: application/json',
                'ZURMO_SESSION_ID: ' . $authenticationData['sessionId'],
                'ZURMO_TOKEN: ' . $authenticationData['token'],
                'ZURMO_API_REQUEST_TYPE: REST',
            );
            $response = ApiRestHelper::createApiCall($this->url.'/app/index.php/contacts/contact/api/read/'.$id, 'GET', $headers);
            // Decode json data
            return var_dump($response);
            $response = json_decode($response, true);
            if ($response['status'] == 'SUCCESS')
            {
                $contactAttributes = $response['data'];
                return $contactAttributes;
                //Do something with contact attributes
            }
            else
            {
                // Error
                $errors = $response['errors'];
                return $errors;
                // Do something with errors
            }
        }

        /*
        |-------------------------------------------------------------------
        | Contact States
        |-------------------------------------------------------------------
        |   
        | List all the available "states" (i.e. status types) for a contact
        |
        */
        public function contactStates()
        {
             $authenticationData = $this->login();
            //Add code to check if user is logged successfully

            $headers = array(
                'Accept: application/json',
                'ZURMO_SESSION_ID: ' . $authenticationData['sessionId'],
                'ZURMO_TOKEN: ' . $authenticationData['token'],
                'ZURMO_API_REQUEST_TYPE: REST',
            );
            $response = ApiRestHelper::createApiCall($this->url.'/app/index.php/contacts/contactState/api/list/', 'GET', $headers);
            // Decode json data
            return var_dump($response);
            $response = json_decode($response, true);
            if ($response['status'] == 'SUCCESS')
            {
                $contactAttributes = $response['data'];
                return $contactAttributes;
                //Do something with contact attributes
            }
            else
            {
                // Error
                $errors = $response['errors'];
                return $errors;
                // Do something with errors
            }
        }
}