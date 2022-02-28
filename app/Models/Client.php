<?php

namespace App\Models;

use App\Clients\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'base_uri'];

    /**
     * by default the command will call the index method otherwise if submitted, the store method
     * @param $action
     */
    public function handle($action)
    {
        $action == 'store' ? $this->store() : $this->index();
    }

    /**
     * once we have the record from the db we need to know what client class to use
     * each client class may have it's own methods and normalisation to build our data
     * when we know what class we are working with, we can call the api using callApi()
     * @return mixed
     */
    public function index()
    {
        return (new ClientFactory())->make($this)->callApi($this);
    }

    /**
     * insert the retrieved api data into the users model
     * Uses the new upsert method introduced in Laravel 8
     * prior to Laravel 8 we would have had to use the firstOrCreate() method
     * and then the fill() method to persist the data if found
     */
    public function store()
    {
        User::upsert(
            $this->index(),
            ['client_id'],
            [
                'email',
                'name',
                'avatar'
            ]
        );
    }
}
