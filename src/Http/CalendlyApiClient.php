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
    }

    // Get All Events of the User (Event Categories/ Options)
    public function getAllEvents($uri) {
        // dd($uri);
        $response = $this->client->request('GET', 'https://api.calendly.com/event_types', [
            'query' => [
                'user' => $uri,
                'count' => $this->count
            ]
        ]);
        
        return $response->toArray()['collection'];
    }

    // Get Active/ Inactive Events of the User (Event Categories/ Options)
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
   
    // Get Details of particular Event based on Event uuid (Event Category/ Option)
    public function getParticularEvent($uuid) {
        //dd($uuid);
        $response = $this->client->request('GET', 'https://api.calendly.com/event_types/'.$uuid.'', [
            // 'query' => [
            //     'uuid' => $uuid
            // ]
        ]);
        
        return $response->toArray()['resource'];
    }

    // Get All Events of the User (Confirmed Events)
    public function getAllScheduledEvents($uri) {
        // dd($uri);
        $response = $this->client->request('GET', 'https://api.calendly.com/scheduled_events', [
            'query' => [
                'user' => $uri,
                'sort' => 'start_time:asc',
                'count' => $this->count
            ]
        ]);
        
        return $response->toArray()['collection'];
    }

    // Get Active/ Inactive Scheduled Events of the User (Confirmed Events)
    public function getSelectedScheduledEvents($uri, $status)
    {
        $response = $this->client->request('GET', 'https://api.calendly.com/scheduled_events', [
            'query' => [
                'user' => $uri,
                'sort' => 'start_time:asc',
                'status' => $status,
                'count' => $this->count
            ]
        ]); 

        return $response->toArray()['collection'];
    }

    // Get Details of particular Scheduled Event based on Event uuid (Confirmed Events)
    public function getParticularScheduledEvent($uuid) {
        //dd($uuid);
        $response = $this->client->request('GET', 'https://api.calendly.com/scheduled_events/'.$uuid.'', [
        ]);
        
        return $response->toArray()['resource'];
    }

    // Cancel Event based on Event uuid
    public function cancelEvent($uuid, $reason)
    {
        $response = $this->client->request('POST', 'https://api.calendly.com/scheduled_events/'.$uuid.'/cancellation', [
            'body' => [
                "reason" => $reason
            ]
        ]);
        
        // dd($response->toArray()['resource']);
        return $response->toArray()['resource'];
    }

    // Get Details of particular Scheduled Event based on Event uuid (Invitee Details of Confirmed Events)
    public function getParticularScheduledEventInvitees($uuid) {
        //dd($uuid);
        $response = $this->client->request('GET', 'https://api.calendly.com/scheduled_events/'.$uuid.'/invitees', [
            'query' => [
                'count' => $this->count
            ]
        ]);
        
        return $response->toArray()['collection'];
    }

    // Get Event name from Event UUID
    public function getParticularScheduledEventName($uuid) {
        return $this->getParticularScheduledEvent($uuid)['name'];
    }

    // Get Details of particular Scheduled Event Invitee based on Event and Invitee uuids
    public function getParticularScheduledEventInviteesByUId($uuid, $uid) {
        $response = $this->client->request('GET', 'https://api.calendly.com/scheduled_events/'.$uuid.'/invitees/'.$uid, [
            'query' => [
                'count' => $this->count
            ]
        ]);
        
        return $response->toArray()['resource'];
    }

    // Mark/ Create a No Show
    public function createNoShow($uuid, $uid) {
        $invitee_uri = "https://api.calendly.com/scheduled_events/" . $uuid . "/invitees/" . $uid;
        $response = $this->client->request('POST', 'https://api.calendly.com/invitee_no_shows', [
            'body' => [
                'invitee' => $invitee_uri
            ]
        ], 201);
        
        return $response->toArray()['resource'];
    }

    // Unmark/ Delete a No Show
    public function deleteNoShow($uuid) {
        $response = $this->client->request('DELETE', 'https://api.calendly.com/invitee_no_shows/'.$uuid, [
        ]);
        
        return $response;
    }

    // Get Invitee No Show
    public function getInviteeNoShow($uuid) {
        $response = $this->client->request('GET', 'https://api.calendly.com/invitee_no_shows/'.$uuid, [
        ]);

        return $response->toArray()['resource'];
    }

    // Get All Events of the User (Event Categories/ Options)
    public function getAllEventsForLink($uri) {
        // dd($uri);
        $response = $this->client->request('GET', 'https://api.calendly.com/event_types', [
            'query' => [
                'user' => $uri,
                'count' => $this->count
            ]
        ]);
        
        return $response->toArray()['collection'];
    }

    // Generate Single Use Scheduling Link
    public function generateLink($owner)
    {
        $response = $this->client->request('POST', 'https://api.calendly.com/scheduling_links', [
            'body' => [
                'max_event_count' => 1,
                'owner' => $owner,
                'owner_type' => "EventType"
            ]
        ], 201);
        
        return $response->toArray()['resource'];
    }
}
