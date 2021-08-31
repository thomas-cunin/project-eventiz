<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="profile")
     */
    public function show(User $profile): Response
    {
        return $this->render('one_profile/index.html.twig', [
            'profile' => $profile,
        ]);
    }

    /**
     * @Route("/profile/me", name="myProfile", priority=1)
     */
    public function showMy(): Response
    {
        return $this->redirectToRoute('profile', [
            'id'=>$this->user->getId()
        ]);
    }
}
