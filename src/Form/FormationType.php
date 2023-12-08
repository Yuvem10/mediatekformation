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
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpClient\HttpClient;

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
            'label' => 'Date'
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

        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
            $form = $event->getForm();
            $lien = $form->get('VideoId')->getData();

            if (str_contains($form->get('VideoId')->getData(), "https://youtu.be/")){
                $lien =  $form->get('VideoId')->getData();
                $lien = str_replace("https://youtu.be/", "", $form->get('VideoId')->getData());  
            }
            
            $url = "https://youtu.be/" . $lien;
            
            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', $url);
            if ($response->getStatusCode() === 200) {
                $content = $response->getContent();
                if (strpos($content, '<meta name="title" content="">') !== false) {
                    $form->get('VideoId')->addError(new FormError('Aucune vidéo ne correspond à ce lien'));
                }else{
                    
                }}
            else{
                $form->get('VideoId')->addError(new FormError('Une erreur serveur est survenue'));
            }
        });
    }



}