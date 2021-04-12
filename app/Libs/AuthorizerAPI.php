<?php

namespace App\Libs;

use App\Models\User;
use GuzzleHttp\Client;

class AuthorizerAPI
{

    private $api;

    private $base_endpoint = 'https://run.mocky.io/v3/';

    public function __construct()
    {
        $this->api = new Client();
    }

    public function consult(User $user)
    {
        $resource = '8fafdd68-a090-496f-8c9a-3442cf30dae6';
        return $this->api->post($this->base_endpoint . $resource);
    }
}
