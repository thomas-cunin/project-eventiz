<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="profile")
     */
    public function showHer(User $profile): Response
    {
        return $this->render('profile/one_profile.html.twig', [
            'profile' => $profile,
        ]);
    }

    /**
     * @Route("/profile/me", name="myProfile", priority=1)
     */
    public function showMy(): Response
    {
        return $this->redirectToRoute('profile', [
            'id'=>$this->getUser()->getId()
        ]);
    }

    // /**
    //  * @Route("/profile/", name="manyProfile",)
    //  */
    // public function showTheir(UserRepository $repo): Response
    // {
    //     $profiles = $repo->findAll();
    //     return $this->render('profile/index.html.twig', [
    //         'profiles' => $profiles,
    //     ]);
    // }
}
