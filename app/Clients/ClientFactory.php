<?php

namespace App\Clients;

use App\Models\Client;
use Illuminate\Support\Str;

class ClientFactory
{
    /**
     * dynamically build a Client class
     * @param Client $client
     * @return mixed
     */
    public function make(Client $client)
    {
        $class = "App\\Clients\\" . Str::studly(Str::replace(' ', '', Str::lower($client->name)));

        return new $class;
    }
}
