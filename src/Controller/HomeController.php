<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager, EtatRepository $etatRepository): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. Date d'ouverture automatique
            $ticket->setDateOuverture(new \DateTime());

            // 2. État par défaut : "Nouveau"
            $etatNouveau = $etatRepository->findOneBy(['nom' => 'Nouveau']);
            if ($etatNouveau) {
                $ticket->setEtat($etatNouveau);
            }

            // 3. Sauvegarde
            $entityManager->persist($ticket);
            $entityManager->flush();

            // 4. Message Flash de succès
            $this->addFlash('success', 'Votre ticket a bien été créé. Notre équipe va traiter votre demande.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}