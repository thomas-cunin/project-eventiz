<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('startAt')
            ->add('endAt')
            ->add('adress')
            ->add('city')
            ->add('content')
            ->add('capacity')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label'=>'name', 
            ])
            ->add('image', FileType::class, [
                'mapped'=>false,
                // 'attr' => ['class' => 'tinymce']
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
