<?php

namespace App\Clients;

use App\Models\Client;
use Illuminate\Support\Facades\Http;

class Reqres
{
    public $data = [];

    /**
     * initiate the calling of the Client api and retrieve the data
     * uses the HTTP client introduced in Laravel 7
     * @param Client $client
     * @return array
     */
    public function callApi(Client $client)
    {
        $response = Http::get($client->base_uri)->json('data');

        $this->noralise($response);

        return $this->data;
    }

    /**
     * normalise the response data
     * in this case for demonstration our model holds both fname and lname in one column
     * we should'nt need to alter our database scheme to accommodate the retrieved data
     * we could be calling multiple client api's that all need to be normalised to accommodate our db schema
     * @param $response
     */
    public function noralise($response)
    {
        foreach ($response as $record){
            $this->data[] = [
                "client_id" => $record['id'],
                "email" => $record['email'],
                "name" => $record['first_name'].' '.$record['last_name'],
                "avatar" => $record['avatar'],
                "password" => bcrypt($record['email']),
            ];
        }
    }
}
