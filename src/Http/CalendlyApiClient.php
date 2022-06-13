<?php

namespace App\Http;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CalendlyApiClient 
{   
    // private const URL = 'https://api.calendly.com';
    private $client;
    private int $count = 100;

    public function __construct(HttpClientInterface $calClient)
    {
        $this->client = $calClient;
    }
    
    // Get Current User Profile
    public function getCurrentUserProfile() {
        $response = $this->client->request('GET', 'https://api.calendly.com/users/me', []); 
        return $response->toArray()['resource'];
        // dd($response->toArray());
    }

    // Get All Events of the User
    public function getAllEvents($uri) {
        // dd($uri);
        $response = $this->client->request('GET', 'https://api.calendly.com/event_types', [
            'query' => [
                'user' => $uri,
                'count' => $this->count
            ]
        ]);
        
        return $response->toArray()['collection'];
        // dd($response->toArray());
    }

    // Get Active/ Inactive Events of the User
    public function getSelectedEvents($uri, $status) {
        // dd($uri);
        // dd($status);
        $response = $this->client->request('GET', 'https://api.calendly.com/event_types', [
            'query' => [
                'user' => $uri,
                'active' => $status,
                'count' => $this->count
            ]
        ]); 
        
        return $response->toArray()['collection'];
    }
   
    // Get Details of particular Event based on Event uuid
    public function getParticularEvent($uuid) {
        //dd($uuid);
        $response = $this->client->request('GET', 'https://api.calendly.com/event_types/'.$uuid.'', [
            // 'query' => [
            //     'uuid' => $uuid
            // ]
        ]);
        
        return $response->toArray()['resource'];
    }

    // Get All Events of the User
    public function getAllScheduledEvents($uri) {
        // dd($uri);
        $response = $this->client->request('GET', 'https://api.calendly.com/scheduled_events', [
            'query' => [
                'user' => $uri,
                'count' => $this->count
            ]
        ]);
        
        return $response->toArray()['collection'];
    }

    public function getSelectedScheduledEvents($uri, $status)
    {
        $response = $this->client->request('GET', 'https://api.calendly.com/scheduled_events', [
            'query' => [
                'user' => $uri,
                'status' => $status,
                'count' => $this->count
            ]
        ]); 

        return $response->toArray()['collection'];
    }

    // Get Details of particular Scheduled Event based on Event uuid
    public function getParticularScheduledEvent($uuid) {
        //dd($uuid);
        $response = $this->client->request('GET', 'https://api.calendly.com/scheduled_events/'.$uuid.'', [
        ]);
        
        return $response->toArray()['resource'];
    }

    // Get Details of particular Scheduled Event based on Event uuid
    public function getParticularScheduledEventInvitees($uuid) {
        //dd($uuid);
        $response = $this->client->request('GET', 'https://api.calendly.com/scheduled_events/'.$uuid.'/invitees', [
            'query' => [
                'count' => $this->count
            ]
        ]);
        
        return $response->toArray()['collection'];
    }

    public function getParticularScheduledEventName($uuid) {
        return $this->getParticularScheduledEvent($uuid)['name'];
    }

    public function getParticularScheduledEventInviteesByUId($uuid, $uid) {
        $response = $this->client->request('GET', 'https://api.calendly.com/scheduled_events/'.$uuid.'/invitees/'.$uid, [
            'query' => [
                'count' => $this->count
            ]
        ]);
        
        // return $response->toArray()['resource'];
        dd($response->toArray()['resource']);
    }
}
