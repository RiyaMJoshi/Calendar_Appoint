<?php

namespace App\Controller;

use App\Http\CalendlyApiClient;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    // Main Profile Page
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, CalendlyApiClient $calendly): Response
    {
       
        $user = $calendly->getCurrentUserProfile();
        // dd($user);
        // $name = $user['name'];

        return $this->render('profile/profile.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user
        ]);
    }
}
