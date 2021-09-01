<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        return $this->render('auth/login.html.twig', [
            
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isSubmitted()){
            $user->setCreatedAt(new \DateTime());
            $password = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setLatitude(7.5);
            $user->setLongitude(5.8);

            $file = $form['image']->getData();
            // compute a random name and try to guess the extension (more secure)
            if ($file){
                $extension = $file->guessExtension();
                if (!$extension) 
                {
                    // extension cannot be guessed
                    $extension = 'bin';
                }
            $fileName = uniqid() .'.'. $extension;
            $image = new Image();
            $image->setName($file->getClientOriginalName());
            $image->setPath($fileName);
            
            $image->setCreatedAt(new \DateTime());
            $image->setUser($user);
            $file->move($this->getParameter('image_dir'), $fileName);
            $em->persist($image);
                
            }
            // dd($user);
            
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
        }
        return $this->render('auth/register.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->render('auth/login.html.twig', [
            
        ]);
    }
}
