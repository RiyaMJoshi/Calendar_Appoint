<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request): Response
    {
        $name = $request->get('invitee_full_name');
        $assignedTo = $request->get('assigned_to');
        $eventType = $request->get('event_type_name');

        return $this->render('profile/profile.html.twig', [
            'controller_name' => 'ProfileController',
            'name' => $name,
            'host' => $assignedTo,
            'event' => $eventType
        ]);
    }
}
