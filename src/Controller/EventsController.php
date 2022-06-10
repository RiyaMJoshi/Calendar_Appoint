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
    // My All Events (Both Active and Inactive)
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
            'event_counts' => $event_counts
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
        $adapter = new ArrayAdapter($events);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($request->query->get('page', 1));
       
        return $this->render('events/myEvents.html.twig', [
            'controller_name' => 'EventsController',
            'pager' => $pagerfanta,
            'event_counts' => $event_counts
        ]);
    }

    // Show Particular Event Details ..............................  STILL REMAINING TO IMPLEMENT IT
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

        // Show Particular Event Details
        #[Route('/scheduled-events', name: 'app_scheduled_events')]
        public function scheduledEvents(Request $request, CalendlyApiClient $calendly): Response
        {
            $user = $calendly->getCurrentUserProfile();
            // dd($slug);
            $events = $calendly->getScheduledEvent($user['uri']);
            // dd($events);
            // $adapter = new ArrayAdapter($events);
            // $pagerfanta = new Pagerfanta($adapter);
            // $pagerfanta->setMaxPerPage(5);
            // $pagerfanta->setCurrentPage($request->query->get('page', 1));
           
            return $this->render('events/showEvent.html.twig', [
                'controller_name' => 'EventsController',
            ]);
        }
}
