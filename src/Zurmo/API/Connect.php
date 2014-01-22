<?php namespace Zurmo\API;

include('RestHelper.php');

class Connect
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
            $response = RestHelper::call($this->url.'/app/index.php/zurmo/api/login', 'POST', $headers);
            
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
    | Creates a new lead in the system. Requires a data array with possible fields:
    | firstName
    | lastName
    | primaryEmail => Array(emailAddress, optOut)
    | description
    | mobilePhone
    | officePhone
    | department
    | jobTitle
    | companyName
    | website
    | state => array(id)
    | 
    |
    */  
        public function new_lead($data)
        {
            /*
            |-------------------------------------
            | Login to API
            |-------------------------------------
            */
               $auth_data = $this->login();
              
                //Add code to check if user is logged successfully
               if(!array_key_exists('sessionId', $auth_data))
               {
                    return var_dump($auth_data);
                    
               }
               
            /*
            |-------------------------------------
            | Set headers
            |-------------------------------------
            */
                $headers = array(
                    'Accept: application/json',
                    'ZURMO_SESSION_ID: ' . $auth_data['sessionId'],
                    'ZURMO_TOKEN: ' . $auth_data['token'],
                    'ZURMO_API_REQUEST_TYPE: REST',
                );
            
            /*
            |-------------------------------------
            | Make API call
            |-------------------------------------
            */
                $response = RestHelper::call($this->url.'/app/index.php/leads/contact/api/create/', 'POST', $headers, array('data' => $data));
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

}