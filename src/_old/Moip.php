<?php

namespace Prothos\Moip;

use Illuminate\Contracts\Foundation\Application;
use GuzzleHttp\Client;
//use Moip\Moip as Api;

class MoipOld
{
    /**
     * The Laravel Application.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     **/
    private $app;

    private $moip;

    // private $client;
    private $headers;
    private $url;

    //const $headers = 'Basic '.base64_encode(config('services.moip.credentials.token').':'.config('services.moip.credentials.key'));
    //const $url = (env('APP_ENV')==='production' ? 'https://api.moip.com.br/v2/' : 'https://sandbox.moip.com.br/v2/');

    /**
     * Class constructor.
     * 
     * @param \Illuminate\Contracts\Foundation\Application $app The Laravel Application.
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        // $this->client = new Client();
        // $this->headers = 'Basic '.base64_encode(config('services.moip.credentials.token').':'.config('services.moip.credentials.key'));
        // $this->url = (env('APP_ENV')==='production' ? 'https://api.moip.com.br/v2/' : 'https://sandbox.moip.com.br/v2/');
    }

    /**
     * Start Moip sdk.
     */
    public function start()
    {

        $this->moip = $this->app->make(Client::class);
        $this->headers = 'Basic '.base64_encode(config('services.moip.credentials.token').':'.config('services.moip.credentials.key'));
        $this->url = (env('APP_ENV')==='production' ? 'https://api.moip.com.br/v2/' : 'https://sandbox.moip.com.br/v2/');
        //$this->moip = $this->app->make(Api::class, [$this->app->make(\Moip\Auth\BasicAuth::class, [config('services.moip.credentials.token'), config('services.moip.credentials.key')]), $this->getHomologated()]);

        return $this;
    }

    public function getRequest($url)
    {
        //return dd((env('APP_ENV')==='production' ? 'https://api.moip.com.br/v2/' : 'https://sandbox.moip.com.br/v2/'));
        // $client = new Client();
        // $headers = 'Basic '.base64_encode(config('services.moip.credentials.token').':'.config('services.moip.credentials.key'));
        // $base_url = (env('APP_ENV')==='production' ? 'https://api.moip.com.br/v2/' : 'https://sandbox.moip.com.br/v2/');
        //return dd($base_url.$url);
        $response = $this->moip->get($this->url.$url, [
            'headers' => [
                'Authorization' => $this->headers,
                ]
        ]);

        return json_decode($response->getBody());
    }

     public function postRequest($url,$form_params)
    {
        $response = $this->moip->post($this->url.$url, [
            'headers' => [
                'Authorization' => $this->headers,
                ],
            'form_params' => $form_params
        ]);

        return json_decode($response->getBody());
    }

    /**
     * Create a new Customer instance.
     *
     * @return \Moip\Resource\Customer
     */
    public function customers()
    {
        return $this->moip->customers();
    }

    /**
     * Create a new Entry instance.
     *
     * @return \Moip\Resource\Entry
     */
    public function entries($limit=100)
    {
        return $this->getRequest('entries?filters=&limit='.$limit);
    }

    /**
     * Create a new Order instance.
     *
     * @return \Moip\Resource\Orders
     */
    public function orders($limit=100)
    {
        //return $this->moip->orders();
        return $this->getRequest('orders?filters=&limit='.$limit);
    }

    /**
     * Create a new Payment instance.
     *
     * @return \Moip\Resource\Payment
     */
    public function payments()
    {
        return $this->moip->payments();
    }

    /**
     * Create a new Multiorders instance.
     *
     * @return \Moip\Resource\Multiorders
     */
    public function multiorders()
    {
        return $this->moip->multiorders();
    }

    public function account_check($account_id=0)
    {
        if ($account_id===0)
        {
            $account_id = $this->getOwnAccountId();
        }
        return $this->getRequest('accounts/'.$account_id);
    }

    public function bankaccounts_check($account_id=0)
    {
        if ($account_id===0)
        {
            $account_id = $this->getOwnAccountId();
        }
        return $this->getRequest('accounts/'.$account_id.'/bankaccounts');
    }

    public function bankaccounts_create($form_params)
    {
        //ex.: ["bankNumber"=> "237","agencyNumber"=> "12345","agencyCheckNumber"=> "0","accountNumber"=> "12345678","accountCheckNumber"=> "7","type"=> "CHECKING","holder"=> ["taxDocument"=>["type"=> "CPF","number"=> "622.134.533-22"],"fullname"=> "Demo Moip"]]
        return $this->postOAuthRequest('accounts/'.$this->getOwnAccountId().'/bankaccounts', $form_params);
    }

    public function balances()
    {
        return $this->getRequest('balances');
    }

    public function getOwnAccountId()
    {
        $entries = $this->entries(1);
        return $entries[0]->moipAccount->account;
    }

    public function transfers($limit=100)
    {
        return $this->getRequest('transfers?filters=&limit='.$limit);
    }


}