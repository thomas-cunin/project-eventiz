<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Comment;
use App\Form\EventType;
use App\Form\CommentType;
use App\Repository\EventRepository;
use App\Service\LocationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EventRepository $repo, LocationService $loc, Request $request): Response
    {
        
        $wts = uniqid();
        if ($request->get('orderby')){
            switch ($request->get('orderby')) {
                
                case 'asc':
                    $events = $repo->findAllByCreatedAtAsc();
                    dump($request->get('orderby'));
                    break;
                
                case 'desc':
                    $events = $repo->findAllByCreatedAtDesc();
                    dump($request->get('orderby'));
                    break;
            }}
            elseif ($request->get('wordToSearch')) {
                $events = $repo->findAllByContent($request->get('wordToSearch'));
                dump($request->get('contentToSearch'));
                $wts = $request->get('wordToSearch');
            } elseif ($request->get('position')) {
                dump($request->get('position'));
                $events = $repo->findAll();
            }
            else {$events = $repo->findAll();}


        return $this->render('event/index.html.twig', [
            'events' => $events,
            'wordToSearch' => $wts,
        ]);
    }

    /**
     * @Route("/event/{id}", name="oneEvent")
     */
    public function show(Event $event, EntityManagerInterface $em, Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $this->getUser())
        {
            $comment->setCreatedAt(new \DateTime());
            $comment->setEvent($event);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();
        }
        return $this->render('event/one_event.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/event/edit/{id}", name="editEvent", priority=1)
     * @Route("/event/add", name="addEvent", priority=1)
     */
    public function edit(Event $event = null, EntityManagerInterface $em, Request $request, LocationService $loc): Response
    {
        $editMode = false;
        if (!$event)
        {
            $event = new Event();
            $editMode = true;
        }
        $form = $this->createForm(EventType::class,$event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {   $coord = explode(",", $request->get('coordonates'));
            $event->setLongitude(0);
            $event->setLatitude(0);
            $event->setAdress('testland');
            $event->setOrganizer($this->getUser());
            $event->setIsCanceled(false);
            $event->setCreatedAt(new \DateTime);
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('oneEvent', ['id'=>$event->getId()]);
        }
        return $this->render('event/edit_event.html.twig',
        [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/event/remove/{id}", name="removeEvent")
     */
    public function remove(Event $event, EntityManagerInterface $em): Response
    {
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('home');
    }
}
