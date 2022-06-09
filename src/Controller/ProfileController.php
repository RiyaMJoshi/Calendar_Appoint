<?php

namespace App\Controller;

use App\Http\CalendlyApiClient;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, CalendlyApiClient $calendly): Response
    {
       
        $user = $calendly->getCurrentUserProfile();
        // dd($user);
        // $name = $user['name'];
        // var_dump('Name:' . $name);


        return $this->render('profile/profile.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user
        ]);
    }

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
        // dd($events);

        return $this->render('profile/myEvents.html.twig', [
            'controller_name' => 'EventsController',
            'pager' => $pagerfanta,
            'event_counts' => $event_counts
        ]);
    }
}
