<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password')
            ->add('displayName')
            ->add('email')
            ->add('likedCategorys', EntityType::class, [
                'class' => Category::class,
                'choice_label'=>'name', 
                'multiple'=>true,
                'expanded'=>true
                ])
            ->add('image', FileType::class, [
                'mapped'=>false,
                // 'attr' => ['class' => 'tinymce']
                'required' => false,
            ])
            ->add('isOrganizer')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
