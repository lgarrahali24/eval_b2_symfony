<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EventRepository $eventRepository, Request $request): Response
    {
        $categorie = $request->query->get('categorie');

        if ($categorie) {
            $events = $eventRepository->findBy(['Validation' => true, 'Categorie' => $categorie]);
        } else {
            // Affiche uniquement les événements validés sur l'accueil
            $events = $eventRepository->findBy(['Validation' => true]);
        }

        return $this->render('home/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/evenement/ajouter', name: 'app_event_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $event->setValidation(false); // Non validé par défaut

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                dd($form->getErrors(true, true));
            }

            // Gestion simple de l'image uploadée
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('kernel.project_dir').'/public/images',
                    $newFilename
                );
                $event->setImage($newFilename);
            }

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', "L'événement a bien été créé et est en attente de validation.");
            return $this->redirectToRoute('app_home');
        }

        return $this->render('event/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/evenement/validation', name: 'app_validate')]
    public function validateList(EventRepository $eventRepository): Response
    {
        // Récupère tous les événements en attente de validation
        $events = $eventRepository->findBy(['Validation' => false]);

        return $this->render('validate/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/evenement/valider/{id}', name: 'app_event_validate_action')]
    public function validateAction(Event $event, EntityManagerInterface $entityManager): Response
    {
        $event->setValidation(true);
        $entityManager->flush();

        $this->addFlash('success', "L'événement a bien été validé !");
        return $this->redirectToRoute('app_validate');
    }

    #[Route('/evenement/supprimer/{id}', name: 'app_event_delete_action')]
    public function deleteAction(Event $event, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($event);
        $entityManager->flush();

        $this->addFlash('success', "L'événement a bien été supprimé.");
        return $this->redirectToRoute('app_validate');
    }

    #[Route('/evenement/{id}', name: 'app_event_show')]
    public function show(?Event $event): Response
    {
        if (!$event) {
            throw $this->createNotFoundException("L'événement demandé n'existe pas.");
        }

        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }
}