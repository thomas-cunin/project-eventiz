<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Subscription;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubscriptionController extends AbstractController
{
    /**
     * @Route("/sub/event/{id}", name="subscription")
     */
    public function sub(UserInterface $user, Event $event, SubscriptionRepository $subRepo, EntityManagerInterface $em): Response
    {
        if ($event->isSubscribedBy($user))
        {
            $sub = $subRepo->findOneBy([
                'user'=>$user,
                'event'=>$event
            ]);
            $em->remove($sub);
            $em->flush();
            $isSub = false;
        } else
        {
            $sub = new Subscription();
            $sub->setCreatedAt(new \DateTime);
            $sub->setUser($user);
            $sub->setEvent($event);
            $em->persist($sub);
            $em->flush();
            $isSub = true;
        }
        $data = [
            'subs' => $subRepo->count([
                'event'=>$event
            ]),
            'isSub' => $isSub
        ];
        return $this->redirectToRoute('oneEvent', [
            'id'=>$event->getId(),
        ]);
    }
    
}
