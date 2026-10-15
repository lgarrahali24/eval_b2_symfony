<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'property_path' => 'Titre',
            ])
            ->add('date', DateTimeType::class, [
                'property_path' => 'Date',
                'widget' => 'single_text',
            ])
            ->add('information', null, [
                'property_path' => 'Description',
            ])
            ->add('category', ChoiceType::class, [
                'property_path' => 'Categorie',
                'choices' => [
                    'Concert' => 'Concert',
                    'Conférence' => 'Conférence',
                    'Atelier' => 'Atelier',
                    'Sport' => 'Sport',
                ],
            ])
            ->add('places', null, [
                'property_path' => 'Nombre_de_Place',
            ])
            ->add('image', FileType::class, [
                'property_path' => 'Image',
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}