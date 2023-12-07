<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Environnement;
use App\Entity\Formation;
use App\Entity\Playlist;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class FormationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
        ->add('title', TextType::class, [
            'label'=> 'Titre',
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false
        ])
        ->add('publishedAt', DateType::class, [
            'widget' => 'single_text',
            'constraints' => [
                new LessThan([
                    'value' => new \DateTime(),
                    'message' => 'La date ne peut pas être postérieure à aujourd\'hui',
                ]),
            ],
        ])
        ->add('playlist', EntityType::class, [
            'class' => Playlist::class,
            'choice_label' => 'name',
            'multiple' => false,
            'required' => true
        ])
        ->add('categories', EntityType::class, [
            'class' => Categorie::class,
            'choice_label' => 'name',
            'multiple' => true,
            'required' => false
        ])
        ->add('VideoId', TextType::class, [
            'label'=> 'Url de la vidéo',
            'required' => true
        ]);
    }



}