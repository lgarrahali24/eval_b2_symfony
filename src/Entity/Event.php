<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Titre = null;

   #[ORM\Column]
   private ?\DateTime $Date = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $Categorie = null;

    #[ORM\Column]
    private ?int $Nombre_de_Place = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?bool $Validation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): static
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->Date;
    }

   public function setDate(?\DateTime $Date): static
{
    $this->Date = $Date;

    return $this;
}

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->Categorie;
    }

    public function setCategorie(string $Categorie): static
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getNombreDePlace(): ?int
    {
        return $this->Nombre_de_Place;
    }

    public function setNombreDePlace(int $Nombre_de_Place): static
    {
        $this->Nombre_de_Place = $Nombre_de_Place;

        return $this;
    }

    public function getimage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $Image): static
    {
        $this->image = $Image;

        return $this;
    }

    public function isValidation(): ?bool
    {
        return $this->Validation;
    }

    public function setValidation(bool $Validation): static
    {
        $this->Validation = $Validation;

        return $this;
    }
}
class AdminController extends AbstractController
{
    #[Route('/validate', name: 'app_validate')]
    #[IsGranted('ROLE_ADMIN')]
    public function validateList(EventRepository $eventRepository): Response
    {
        // 1. Récupérer uniquement les événements non validés (Validation = false)
        $events = $eventRepository->findBy(['Validation' => false]);

        return $this->render('validate/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/validate/{id}', name: 'app_validate_action', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function validateEvent(Event $event, EntityManagerInterface $entityManager): Response
    {
        // 2. Basculer le booléen à true
        $event->setValidation(true);
        
        // Enregistrer les modifications en base de données
        $entityManager->flush();

        $this->addFlash('success', "L'événement a bien été validé avec succès !");

        // Rediriger vers la page de validation
        return $this->redirectToRoute('app_validate');
    }
}
