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
        $pagerfanta = $this->makePager($request, $events);

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
        $pagerfanta = $this->makePager($request, $events);
       
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
        $event = $calendly->getParticularEvent($uuid);
        // dd($event);
               
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
        $events = $calendly->getAllScheduledEvents($user['uri']);
        $event_counts = count($events);
        // dd($events);    
        $pagerfanta = $this->makePager($request, $events);
           
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
        $user = $calendly->getCurrentUserProfile();
        if ($status == 0) {
            $status = "canceled";
        }
        else {
            $status = "active";
        }

        $events = $calendly->getSelectedScheduledEvents($user['uri'], $status);
        $event_counts = count($events);
        $pagerfanta = $this->makePager($request, $events);
        
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
        $event = $calendly->getParticularScheduledEvent($uuid);
        // dd($event);
        $event_type_id = substr($event['event_type'], 37);
        // dd($event_type_id);
        $event_duration = $calendly->getParticularEvent($event_type_id)['duration'];
        // dd($event_duration);
        
        return $this->render('events/scheduled_events/showScheduledEvent.html.twig', [
            'controller_name' => 'EventsController',
            'event' => $event,
            'duration' => $event_duration,
            'slicer' => 42
        ]);
    }

    // Cancel already Scheduled Event based on Event UUID
    #[Route('/scheduled-events/{uuid}/cancellation', name: 'app_cancel_scheduled_event')]
    public function cancelScheduledEvent($uuid, Request $request, CalendlyApiClient $calendly): Response
    {
        // dd($uuid);
        $reason = $request->get("reason");
        // dd($reason);
        $event = $calendly->cancelEvent($uuid, $reason);
        // dd($event);
               
        return $this->redirectToRoute('app_show_scheduled_event', [
            'uuid' => $uuid
        ]);
    }
    
    // Show Invitees of Scheduled Event based on Event UUID
    #[Route('/scheduled-events/{uuid}/invitees', name: 'app_show_scheduled_event_invitees')]
    public function showScheduledEventInvitees($uuid, Request $request, CalendlyApiClient $calendly): Response
    {
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
        // dd($uuid);
        $invitees = $calendly->getParticularScheduledEventInviteesByUId($uuid, $uid);
        // dd($invitees);
        $event_name = $calendly->getParticularScheduledEventName($uuid);
        // dd($event_name);
               
        return $this->render('events/scheduled_events/showScheduledEventInviteesByUid.html.twig', [
            'controller_name' => 'EventsController',
            'invitee' => $invitees,
            'event_name' => $event_name,
            'uuid' => $uuid,
            'uid' => $uid
        ]);
    }

    // Create Invitee No Show based on Scheduled Event (based on Event UUID and Invitee UUID)
    #[Route('/invitee-no-shows/{uuid}/invitees/{uid}', name: 'app_create_invitee_no_show')]
    public function createInviteeNoShow($uuid, $uid, Request $request, CalendlyApiClient $calendly): Response
    {
        $no_show = $calendly->createNoShow($uuid, $uid);
        // dd($no_show);
        $event_name = $calendly->getParticularScheduledEventName($uuid);
        // dd($event_name);
        return $this->redirectToRoute('app_show_scheduled_event_invitees_by_uid', ['uuid' => $uuid, 'uid' => $uid]);
    }

    // Delete Invitee No Show based on No Show Event UUID
    #[Route('/invitee-no-shows/{no_show_uuid}', name: 'app_delete_invitee_no_show')]
    public function deleteInviteeNoShow($no_show_uuid, Request $request, CalendlyApiClient $calendly): Response
    {
        $no_show = $this->showInviteeNoShow($no_show_uuid, $calendly);
        $uuid = substr($no_show['invitee'], 42, 36);
        $uid = substr($no_show['invitee'], 88);
        // dd($no_show);
        $dlt_no_show = $calendly->deleteNoShow($no_show_uuid);
        // dd($dlt_no_show);
        $event_name = $calendly->getParticularScheduledEventName($uuid);
        // dd($event_name);
        return $this->redirectToRoute('app_show_scheduled_event_invitees_by_uid', ['uuid' => $uuid, 'uid' => $uid]);
    }

    // Get Invitee No Show based on No Show Event UUID
    #[Route('/invitee-no-shows/{uuid}', name: 'app_get_invitee_no_show')]
    public function showInviteeNoShow($uuid, CalendlyApiClient $calendly): array
    {
        // dd($uuid);
        $no_show = $calendly->getInviteeNoShow($uuid);
        // dd($no_show);
        return $no_show;
    }

    // Get all Events to provide option to generate single use scheduling links
    #[Route('/scheduling-links', name: 'app_scheduling_links')]
    public function createSchedulingLink(Request $request, CalendlyApiClient $calendly): Response
    {
        $user = $calendly->getCurrentUserProfile();
        $events = $calendly->getAllEvents($user['uri']);
        $event_counts = count($events);
        // dd($events);
       
        return $this->render('events/scheduled_events/scheduling_links.html.twig', [
            'controller_name' => 'EventsController',
            'events' => $events,
            'event_counts' => $event_counts,
            'slicer' => 37
        ]);
    }

    // Get all Events to provide option to generate single use scheduling links
    #[Route('/scheduling-links/generate', name: 'app_generate_scheduling_link')]
    public function generateSchedulingLink(Request $request, CalendlyApiClient $calendly): Response
    {
        $owner = $request->get('inputGroupSelectEvent');
        $event_name = $request->get('event_text');
        // dd($event_name);
        // dd($owner);
        $user = $calendly->getCurrentUserProfile();
        $events = $calendly->getAllEvents($user['uri']);
        $event_counts = count($events);
        $event_link = $calendly->generateLink($owner);
        // dd($event_link);

        return $this->render('events/scheduled_events/scheduling_links.html.twig', [
            'controller_name' => 'EventsController',
            'events' => $events,
            'event_counts' => $event_counts,
            'event_link' => $event_link,
            'event_name' => $event_name
        ]);
    }

    public function makePager($request, $events)
    {
        $adapter = new ArrayAdapter($events);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($request->query->get('page', 1));

        return $pagerfanta;
    }
}
