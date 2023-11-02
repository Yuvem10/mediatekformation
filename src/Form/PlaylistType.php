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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class PlaylistType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
        ->add('name', TextType::class, [
            'label'=> 'Titre de la playlist',
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false
        ]);
    }



}