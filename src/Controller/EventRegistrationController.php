<?php

namespace App\Controller;

use App\Entity\EventRegistration;
use App\Form\EventRegistrationType;
use App\Repository\EventRegistrationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Entity\Events;
use Symfony\Component\HttpFoundation\RequestStack;


#[Route('/event/registration')]
final class EventRegistrationController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }


    #[Route(name: 'app_event_registration_index', methods: ['GET'])]
    public function index(EventRegistrationRepository $eventRegistrationRepository): Response
    {
        return $this->render('event_registration/index.html.twig', [
            'event_registrations' => $eventRegistrationRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_event_registration_new', methods: ['GET', 'POST'])]
    public function register( int $id, EntityManagerInterface $em,UserRepository $userRepository,  Request $request ): Response 
    {

        $session = $this->requestStack->getSession();
        $user_id = $session->get('id');

        $event = $em->getRepository(Events::class)->find($id);
        $user = $userRepository->findOneById($user_id); // Replace with real user logic
        
        $existingRegistration = $em->getRepository(EventRegistration::class)->findOneBy([
            'event' => $event,
            'user' => $user,
        ]);

        if ($existingRegistration) {
            $this->addFlash('info', 'You are already registered for this event.');
            return $this->redirectToRoute('app_events_show', ['id' => $event->getId()]);
        }
        // Create form with empty Reservation object
        $reservation = new EventRegistration();
        $form = $this->createForm(EventRegistrationType::class, $reservation);
    
        // Handle form submission
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Associate entities
            $reservation->setEvent($event);
            $reservation->setUser($user);
    
            // Persist and flush
            $em->persist($reservation);
            $em->flush();
    
            // Redirect on success
            $this->addFlash('success', 'Reservation successful!');
            return $this->redirectToRoute('app_events_index');
        }

        // Render form with errors
        return $this->render('event_registration/new.html.twig', [
            'event' => $event,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_event_registration_show', methods: ['GET'])]
    public function show(EventRegistration $eventRegistration): Response
    {
        return $this->render('admin_event/show.html.twig', [
            'event_registration' => $eventRegistration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_registration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EventRegistration $eventRegistration, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventRegistrationType::class, $eventRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_events_my_registration', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event_registration/edit.html.twig', [
            'event_registration' => $eventRegistration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_registration_delete', methods: ['POST'])]
    public function delete(Request $request, EventRegistration $eventRegistration, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eventRegistration->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($eventRegistration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_events_my_registration', [], Response::HTTP_SEE_OTHER);
    }


        public function registerUserForEvent(EntityManagerInterface $em, User $user, Events $event): Response
    {
        $registration = new EventRegistration();
        $registration->setUser($user);
        $registration->setEvent($event);
        $registration->setRegistrationDate(new \DateTime());
        $registration->setStatus('confirmed');

        $em->persist($registration);
        $em->flush();

        return new Response('User registered successfully!');
    }

    #[Route('/{id}/update-status', name: 'update_event_registration_status', methods: ['POST'])]
    public function updateStatus(Request $request, EventRegistration $eventRegistration, EntityManagerInterface $entityManager): Response
    {
        // Get the new status from the form submission
        $newStatus = $request->get('status');
        
        // Get the associated Event
        $event = $eventRegistration->getEvent();
        
        // Get the current status of the registration
        $currentStatus = $eventRegistration->getStatus();
        
        // If the status is changed, update the status and adjust seats available
        if ($newStatus) {
            $eventRegistration->setStatus($newStatus);
    
            // Handle seat adjustments based on status change
            // If status is being changed to 'Confirmed' from another status (Pending, Canceled)
            if ($newStatus === 'Confirmed' && $currentStatus !== 'Confirmed') {
                // Decrease the available seats when status is set to confirmed
                $seatsAvailable = $event->getSeatsAvailable();
                if ($seatsAvailable > $eventRegistration->getPlacesReserved()) {
                    $event->setSeatsAvailable($seatsAvailable - $eventRegistration->getPlacesReserved()); // Decrease by 1
                } else {
                    $this->addFlash('error', 'No seats available to confirm the registration!');
                    return $this->redirectToRoute('app_event_registration_show', ['id' => $eventRegistration->getId()]);
                }
            }
    
            // If status was 'Confirmed' and is now 'Pending' or 'Canceled', increment the seats
            if ($currentStatus === 'Confirmed' && ($newStatus === 'Pending' || $newStatus === 'Canceled')) {
                $seatsAvailable = $event->getSeatsAvailable();
                $event->setSeatsAvailable($seatsAvailable + $eventRegistration->getPlacesReserved()); // Increase by 1
            }
    
            // Persist the changes to the database
            $entityManager->flush();
            
            // Add a flash message for feedback
            $this->addFlash('success', 'Event registration status updated!');
        } else {
            $this->addFlash('error', 'Failed to update the status.');
        }
    
        // Redirect back to the event registration page
        return $this->redirectToRoute('app_event_registration_show', ['id' => $eventRegistration->getId()]);
    }
    


}
