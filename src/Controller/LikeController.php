<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Event;
use App\Entity\Comment;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikeController extends AbstractController
{
    /**
     * @Route("/like/event/{id}", name="likeEvent", priority=1)
     */
    public function likeEvent(UserInterface $user, Event $event, EntityManagerInterface $em, LikeRepository $likeRepo): Response
    {
        if ($event->isLikedBy($user))
        {
            $like = $likeRepo->findOneBy([
                'user'=>$user,
                'event'=>$event
            ]);
            $em->remove($like);
            $em->flush();
            $isLiked = false;
        } else
        {
            $like = new Like();
            $like->setCreatedAt(new \DateTime);
            $like->setUser($user);
            $like->setEvent($event);
            $em->persist($like);
            $em->flush();
            $isLiked = true;
        }
        $data = [
            'likes' => $likeRepo->count([
                'event'=>$event
            ]),
            'isLiked' => $isLiked
        ];
        return $this->json($data, 200);
    }

    /**
     * @Route("/like/comment/{id}", name="likeComment", priority=1)
     */
    public function likeComment(UserInterface $user, Comment $comment, EntityManagerInterface $em, LikeRepository $likeRepo): Response
    {
        if ($comment->isLikedBy($user))
        {
            $like = $likeRepo->findOneBy([
                'user'=>$user,
                'comment'=>$comment
            ]);
            $em->remove($like);
            $em->flush();
            $isLiked = false;
        } else
        {
            $like = new Like();
            $like->setUser($user);
            $like->setComment($comment);
            $em->persist($like);
            $em->flush();
            $isLiked = true;
        }
        $data = [
            'likes' => $likeRepo->count([
                'comment'=>$comment
            ]),
            'isLiked' => $isLiked
        ];
        return $this->json($data, 200);
    }
}
