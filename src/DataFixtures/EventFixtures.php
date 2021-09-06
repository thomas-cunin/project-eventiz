<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Like;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Image;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Subscription;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $em)
    {
        $faker = \Faker\Factory::create('fr_FR');
        $usersO = [];
        $usersNO = [];
        for ($i=0; $i < 25; $i++) { 
            $user = new User;
            $user->setUsername($faker->unique()->userName);
            $user->setIsOrganizer($faker->boolean($chanceOfGettingTrue = 50));
            if ($user->getIsOrganizer()){
                $user->setDisplayName($faker->unique()->company);
                $user->setEmail($faker->unique()->companyEmail);
            } else {
                $user->setDisplayName($faker->unique()->name);
                $user->setEmail($faker->unique()->freeEmail);
            }
            $password = '$2y$13$RtJkz9.primIiuykg0Jp1ulnGpsmhfJ9G2MhhlWOyiSksXcM8riZi';
            $user->setPassword($password);
            
            $user->setCreatedAt(new \DateTime());
            $image = new Image;
            $image->setUser($user);
            $image->setPath('default.jpg');
            $image->setCreatedAt(new \DateTime());
            $image->setName('A default image for user');
            $em->persist($image);
            $em->persist($user);
            if (!$user->getIsOrganizer()){
                array_push($usersNO, $user);
            } else {
                array_push($usersO, $user);
            }
            

        }
        $catsNames=[
            'Musique',
            'Sport',
            'Art & culture',
            'Conférence',
            'Visite & portes ouvertes',
            'Brocante',
            'Maniféstation',
            
        ];
        foreach ($catsNames as $catName) {
            $cat = new Category();
            $cat->setName($catName);
            $em->persist($cat);
            for ($i=0; $i < rand(3, 6); $i++) { 
                $event= new Event;
                $event->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true));
                $event->setStartAt($faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = 'Europe/Paris'));
                $event->setEndAt($faker->dateTimeBetween($startDate = $event->getStartAt(), $endDate = '+1 years', $timezone = 'Europe/Paris'));
                $event->setCreatedAt(new \DateTime());
                $event->setContent($faker->text);
                $event->setIsCanceled(false);
                $event->setCategory($cat);
                $event->setOrganizer($usersO[random_int(0, count($usersO) - 1)]);
                $event->setAdress($faker->address);
                if (rand(0,5) >= 1.7){
                    $event->setCapacity(random_int(50, 150));
                }
                $em->persist($event);
                foreach ($usersNO as $user) {
                    if (rand(0,2) <= 1){
                        $like = new Like();
                        $like->setUser($user);
                        $like->setEvent($event);
                        $like->setCreatedAt(new \DateTime());
                        $em->persist($like);
                        
                    }
                    if (rand(0,2) <= 1){
                        $sub = new Subscription();
                        $sub->setEvent($event);
                        $sub->setUser($user);
                        $sub->setCreatedAt(new \DateTime());
                        $em->persist($sub);
                    }
                } 
                for ($i=0; $i < rand(0, 6); $i++) { 
                    $comment = new Comment;
                    $comment->setEvent($event);
                    $comment->setUser($usersNO[random_int(0,count($usersNO) - 1)]);
                    $comment->setCreatedAt(new \DateTime());
                    $comment->setContent($faker->text($maxNbChars = 200, $indexSize = 2));
                    $em->persist($comment);
                }

            }
        }

        $em->flush();
    }
}
