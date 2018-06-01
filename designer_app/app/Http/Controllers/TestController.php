<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $user;
    protected $request;


    public function __construct(User $user, Request $request)
    {
        $this->user     = $user;
    }

    //--------------------------------------------------------------------------

    public function index()
    {

        $client = new Client();
        $client->setDefaultOption('verify', false);
        $res = $client->get('https://www.reddit.com/');

        echo $res->getStatusCode();
// "200"
echo $res->getHeader('content-type');
// 'application/json; charset=utf8'
echo $res->getBody();

    }

    //--------------------------------------------------------------------------
  
}
