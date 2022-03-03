<?php

namespace App\Models;

use App\Clients\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\RequestException;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'base_uri'];

    /**
     * by default the command will call the index method otherwise if submitted, the store method
     * @param $action
     */
    public function handle($action, int $page, bool $all)
    {
        $action == 'store' ? $this->store($page, $all) : $this->index($page, $all);
    }

    /**
     * once we have the record from the db we need to know what client class to use
     * each client class may have it's own methods and normalisation to build our data
     * when we know what class we are working with, we can call the api using callApi()
     * @return mixed
     */
    public function index($page, $all)
    {
        try {
            return (new ClientFactory())->make($this)->callApi($this, $page, $all);
        } catch (\Exception $e) {
            // exception for ClientFactory
            echo $e->getMessage();
            exit();
        } catch (RequestException $e) {
            // exception for HTTP client
            echo $e->getMessage();
            exit();
        }
    }

    /**
     * insert the retrieved api data into the users model
     * Uses the new upsert method introduced in Laravel 8
     * prior to Laravel 8 we would have had to use the firstOrCreate() method
     * and then the fill() method to persist the data if found
     */
    public function store($page, $all)
    {
        // used only for testing the artisan command to check if records are being updated
        //User::truncate();

        $data = $this->index($page, $all);

        if (is_array($data) && count($data) > 0) {
            User::upsert(
                $data,
                ['client_id'],
                [
                    'email',
                    'name',
                    'avatar'
                ]
            );
        }
    }
}
