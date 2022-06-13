<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Http\CalendlyApiClient;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    // My All Events (Mix of Both Active and Inactive)
    #[Route('/my-events', name: 'app_my_events')]
    public function myEvents(Request $request, CalendlyApiClient $calendly): Response
    {
        $user = $calendly->getCurrentUserProfile();
        $events = $calendly->getAllEvents($user['uri']);
        $event_counts = count($events);
        // dd($events);
        $adapter = new ArrayAdapter($events);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($request->query->get('page', 1));

        return $this->render('events/myEvents.html.twig', [
            'controller_name' => 'EventsController',
            'pager' => $pagerfanta,
            'event_counts' => $event_counts,
            'slicer' => 37
        ]);
    }

    // My Status Based Events (Either Active Or Inactive)
    #[Route('/my-events/{status<0|1>}', name: 'app_my_selected_events')]
    public function mySelecetdEvents($status, Request $request, CalendlyApiClient $calendly): Response
    {
        // dd($status);
        $user = $calendly->getCurrentUserProfile();
        $events = $calendly->getSelectedEvents($user['uri'], $status);
        $event_counts = count($events);
        // $pagerfanta = makePager($request, $events);
        $adapter = new ArrayAdapter($events);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($request->query->get('page', 1));
       
        return $this->render('events/myEvents.html.twig', [
            'controller_name' => 'EventsController',
            'pager' => $pagerfanta,
            'event_counts' => $event_counts,
            'slicer' => 37
        ]);
    }

    // Show Particular Event Details
    #[Route('/events/{uuid}', name: 'app_show_event')]
    public function showEvent($uuid, Request $request, CalendlyApiClient $calendly): Response
    {
        $user = $calendly->getCurrentUserProfile();
        // dd($uuid);
        $event = $calendly->getParticularEvent($uuid);
        // dd($events);
               
        return $this->render('events/showEvent.html.twig', [
            'controller_name' => 'EventsController',
            'event' => $event
        ]);
    }
    
    // Show Scheduled Event Details
    #[Route('/scheduled-events', name: 'app_scheduled_events')]
    public function scheduledEvents(Request $request, CalendlyApiClient $calendly): Response
    {
        $user = $calendly->getCurrentUserProfile();
        // dd($slug);
        $events = $calendly->getAllScheduledEvents($user['uri']);
        $event_counts = count($events);
        // dd($events);    
        // $pagerfanta = makePager($request, $events);
        $adapter = new ArrayAdapter($events);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($request->query->get('page', 1));
           
        return $this->render('events/scheduled_events/myScheduledEvents.html.twig', [
            'controller_name' => 'EventsController',
            'pager' => $pagerfanta,
            'event_counts' => $event_counts,
            'slicer' => 42
        ]);
    }

    // My Status Based Selected Events (Either Active Or Inactive)
    #[Route('/scheduled-events/{status<0|1>}', name: 'app_selected_scheduled_events')]
    public function mySelecetdScheduledEvents($status, Request $request, CalendlyApiClient $calendly): Response
    {
        // dd($status);
        $user = $calendly->getCurrentUserProfile();
        if ($status == 0) {
            $status = "canceled";
        }
        else {
            $status = "active";
        }

        $events = $calendly->getSelectedScheduledEvents($user['uri'], $status);
        $event_counts = count($events);
        // $pagerfanta = makePager($request, $events);
        $adapter = new ArrayAdapter($events);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($request->query->get('page', 1));
        
        return $this->render('events/scheduled_events/myScheduledEvents.html.twig', [
            'controller_name' => 'EventsController',
            'pager' => $pagerfanta,
            'event_counts' => $event_counts,
            'slicer' => 42
        ]);
    }

    // Show Particular Scheduled Event Details
    #[Route('/scheduled-events/{uuid}', name: 'app_show_scheduled_event')]
    public function showScheduledEvent($uuid, Request $request, CalendlyApiClient $calendly): Response
    {
        $user = $calendly->getCurrentUserProfile();
        // dd($uuid);
        $event = $calendly->getParticularScheduledEvent($uuid);
        // dd($event);
               
        return $this->render('events/scheduled_events/showScheduledEvent.html.twig', [
            'controller_name' => 'EventsController',
            'event' => $event,
            'slicer' => 42
        ]);
    }

    // Show Invitees of Scheduled Event based on Event UUID
    #[Route('/scheduled-events/{uuid}/invitees', name: 'app_show_scheduled_event_invitees')]
    public function showScheduledEventInvitees($uuid, Request $request, CalendlyApiClient $calendly): Response
    {
        $user = $calendly->getCurrentUserProfile();
        // dd($uuid);
        $invitees = $calendly->getParticularScheduledEventInvitees($uuid);
        // dd($invitees);
        $event_name = $calendly->getParticularScheduledEventName($uuid);
        // dd($event_name);
               
        return $this->render('events/scheduled_events/showScheduledEventInvitees.html.twig', [
            'controller_name' => 'EventsController',
            'invitees' => $invitees,
            'event_name' => $event_name,
            'uuid' => $uuid
        ]);
    }

    // Show Invitees of Scheduled Event based on Event UUID and Invitee UUID
    #[Route('/scheduled-events/{uuid}/invitees/{uid}', name: 'app_show_scheduled_event_invitees_by_uid')]
    public function showScheduledEventInviteesByUId($uuid, $uid, Request $request, CalendlyApiClient $calendly): Response
    {
        $user = $calendly->getCurrentUserProfile();
        // dd($uuid);
        $invitees = $calendly->getParticularScheduledEventInviteesByUId($uuid, $uid);
        dd($invitees);
        $event_name = $calendly->getParticularScheduledEventName($uuid);
        // dd($event_name);
               
        return $this->render('events/scheduled_events/showScheduledEventInviteesByUid.html.twig', [
            'controller_name' => 'EventsController',
            'invitees' => $invitees,
            'event_name' => $event_name,
            'uuid' => $uuid,
            'uid' => $uid
        ]);
    }
}
