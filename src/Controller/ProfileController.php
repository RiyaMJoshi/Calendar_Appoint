<?php

namespace App\Controller;

use App\Http\CalendlyApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request,CalendlyApiClient $calendly): Response
    {
       
        $user = $calendly->fetchUserProfile();
        // dd($user);
//         ProfileController.php on line 19:
// array:10 [▼
//   "avatar_url" => "https://d3v0px0pttie1i.cloudfront.net/uploads/user/avatar/18656265/bd85d019.jpg"
//   "created_at" => "2022-05-30T05:17:16.190519Z"
//   "current_organization" => "https://api.calendly.com/organizations/ad4e110a-da99-4d1c-95b0-51845c01ca13"
//   "email" => "riyajoshi.190673107028@gmail.com"
//   "name" => "Riya Joshi"
//   "scheduling_url" => "https://calendly.com/riyajoshi-sal"
//   "slug" => "riyajoshi-sal"
//   "timezone" => "Asia/Calcutta"
//   "updated_at" => "2022-06-07T06:28:41.042008Z"
//   "uri" => "https://api.calendly.com/users/7744d9d1-5656-4d15-a974-1fdc81433b2a"
// ]
        // $name = $user['name'];
        // var_dump('Name:' . $name);


        return $this->render('profile/profile.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user
        ]);
    }

    #[Route('/my-schedule', name: 'app_my_schedule')]
    public function mySchedule(Request $request): Response
    {
        $name = $request->get('invitee_full_name');
        $assignedTo = $request->get('assigned_to');
        $eventType = $request->get('event_type_name');

        return $this->render('profile/profile.html.twig', [
            'controller_name' => 'ProfileController'
        ]);
    }
}
