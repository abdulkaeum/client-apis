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
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function callApi(Client $client, int $page, bool $all)
    {
        // initial call
        $response = Http::get($client->base_uri)->throw();

        // is the response status 200 and do we have any records
        if ($response->ok() && $response->json('total') > 0) {

            // how many pages do we have
            $total_pages = $response->json('total_pages');

            // have we supplied a page that does not exist
            if ($page > $total_pages) return null;

            if ($all) {
                // perform full set page calls / start from page=1 and increment for each page we have
                for ($i = 1; $i <= $total_pages; $i++) {
                    $response = Http::get($client->base_uri . '?page=' . $i)->throw()->json('data');

                    // send data for normalisation for each page
                    $this->normalise($response);
                }
            } else {
                // perform single page calls
                $response = Http::get($client->base_uri . '?page=' . $page)->throw()->json('data');

                // send data for normalisation for the page
                $this->normalise($response);
            }

            return $this->data;
        }
    }

    /**
     * normalise the response data
     * in this case for demonstration our model holds both fname and lname in one column
     * we should'nt need to alter our database scheme to accommodate the retrieved data
     * we could be calling multiple client api's that all need to be normalised to accommodate our db schema
     * @param $response
     */
    public function normalise($response)
    {
        foreach ($response as $record) {
            $this->data[] = [
                "client_id" => $record['id'],
                "email" => $record['email'],
                "name" => $record['first_name'] . ' ' . $record['last_name'],
                "avatar" => $record['avatar'],
                "password" => bcrypt($record['email']),
            ];
        }
    }
}
