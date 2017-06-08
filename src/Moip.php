<?php

namespace Prothos\Moip;

use Illuminate\Contracts\Foundation\Application;
use GuzzleHttp\Client;
//use Moip\Moip as Api;

class Moip
{
    /**
     * The Laravel Application.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     **/
    private $app;

    /**
     * Class Moip sdk.
     *
     * @var \Moip\Moip
     **/
    private $moip;

    /**
     * Class constructor.
     * 
     * @param \Illuminate\Contracts\Foundation\Application $app The Laravel Application.
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->client = new Client();
        $this->headers = 'Basic '.base64_encode(config('services.moip.credentials.token').':'.config('services.moip.credentials.key'));
        $this->url = (config('APP_ENV')==='production' ? 'https://api.moip.com.br/v2/' : 'https://sandbox.moip.com.br/v2/')
    }

    /**
     * Start Moip sdk.
     */
    public function start()
    {
        $client = new Client();
        $response = $client->post('https://meuconsumo.laager.com.br/oauth/token', [
            'headers' => [
                'Authorization' => $this->headers,
                ],
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => env('LAAGER_CLIENT_ID'),
                'client_secret' => env('LAAGER_CLIENT_SECRET'),
                'redirect_uri' => 'urn:ietf:wg:oauth:2.0:oob',
                'username' => env('LAAGER_USERNAME'),
                'password' => env('LAAGER_PASSWORD'),
            ]
        ]);

        $params = json_decode($response->getBody());
        //$this->moip = $this->app->make(Api::class, [$this->app->make(\Moip\Auth\BasicAuth::class, [config('services.moip.credentials.token'), config('services.moip.credentials.key')]), $this->getHomologated()]);

        return $this;
    }

    public function getRequest($url)
    {
        $response = $this->client->get($this->url.$url, [
            'headers' => [
                'Authorization' => $this->headers,
                ]
        ]);

        return json_decode($response->getBody());
    }

     public function postRequest($url,$form_params)
    {
        $response = $this->client->post($this->url.$url, [
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
    public function entries()
    {
        return $this->moip->entries();
    }

    /**
     * Create a new Order instance.
     *
     * @return \Moip\Resource\Orders
     */
    public function orders()
    {
        return $this->moip->orders();
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

    /**
     * Get endpoint of request.
     * 
     * @return \Moip\Moip::ENDPOINT_PRODUCTION|\Moip\Moip::ENDPOINT_SANDBOX
     */
    private function getHomologated()
    {
        return config('services.moip.homologated') === true ? Api::ENDPOINT_PRODUCTION : Api::ENDPOINT_SANDBOX;
    }
}
