<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ValidateController extends AbstractController
{
    #[Route('/validate', name: 'app_validate')]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findBy(['Validation' => false]);

        return $this->render('validate/index.html.twig', [
            'events' => $events,
        ]);
    }
}