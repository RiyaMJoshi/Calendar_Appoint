<?php

namespace App\Http;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CalendlyApiClient 
{   
    // private const URL = 'https://api.calendly.com';
    private $client;
    public function __construct(HttpClientInterface $calClient)
    {
        $this->client = $calClient;
    }
    
    public function getCurrentUserProfile() {
        $response = $this->client->request('GET', 'https://api.calendly.com/users/me', []); 
        return $response->toArray()['resource'];
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
        // dd($response->toArray());
    }

    public function getSelectedEvents($uri, $status) {
        // dd($uri);
        // dd($status);
        $response = $this->client->request('GET', 'https://api.calendly.com/event_types', [
            'query' => [
                'user' => $uri,
                'active' => $status
            ]
        ]); 
        
        return $response->toArray()['collection'];
    }
   
    public function getParticularEvent($uuid) {
        //dd($uuid);
        $response = $this->client->request('GET', 'https://api.calendly.com/event_types/'.$uuid.'', [
            // 'query' => [
            //     'uuid' => $uuid
            // ]
        ]);
        
        return $response->toArray()['resource'];
        // dd($response->toArray()['resource']);
    }

    public function getScheduledEvent()
    {
        # code...
    }
}
