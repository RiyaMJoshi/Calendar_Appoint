<?php

namespace App\Http;

use Symfony\Contracts\HttpClient\HttpClientInterface;

// use Symfony\Contracts\HttpClient\HttpClientInterface;

class CalendlyApiClient 
{   
    // private const URL = 'https://api.calendly.com';
    // private const URL = 'https://api.calendly.com/users/me';
    private $client;
    public function __construct(HttpClientInterface $calClient)
    {
        $this->client = $calClient;
    }
    
    public function getCurrentUserProfile() {
        // dd($calendlyToken);
        $response = $this->client->request('GET', 'https://api.calendly.com/users/me', []); 
        return $response->toArray()['resource'];
        // $headers = $response->getHeaders();
        // dd($response->toArray());
    }

    public function getAllEvents($uri) {
        // dd($uri);
        $response = $this->client->request('GET', 'https://api.calendly.com/event_types', [
            'query' => [
                'user' => $uri
            ]
        ]); 
        return $response->toArray()['collection'];
        // $headers = $response->getHeaders();
        // dd($response->toArray());
    }
   
}
