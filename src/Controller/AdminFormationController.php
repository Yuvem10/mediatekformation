<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Form\FormationType;
use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\ElseIf_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Goutte\Client;



/**
 * Controleur des formations
 *
 * @author emds
 */
class AdminFormationController extends AbstractController
{

    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;

    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * @Route("/admin", name="admin")
     * @return Response
     */
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/index.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/tri/{champ}/{ordre}/{table}", name="admin.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    public function sort($champ, $ordre, $table = ""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/index.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/recherche/{champ}/{table}", name="admin.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/index.html.twig", [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * @Route("/admin/suppr/{id}", name="admin.suppr")
     * @param int $id
     * @param Formation $formation
     * @return Response
     */
    public function suppr(int $id): Response
    {
        $formation = $this->formationRepository->find($id);
        $this->formationRepository->remove($formation, true);
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/edit/{id}", name="admin.edit")
     * @param Formation $formation
     * @return Response
     */
    public function edit(Formation $formation, Request $request): Response
    {

        $formFormation = $this->createForm(FormationType::class, $formation);

        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute('admin');
        }

        return $this->render("/admin/edit.html.twig", [
            'formation' => $formation,
            'form' => $formFormation->createView()
        ]);
    }

    /**
     * @Route("/admin/ajout", name="admin.ajout")
     */

    public function ajout(Request $request, EntityManagerInterface $manager, CategorieRepository $categorieRepository, PlaylistRepository $playlistRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createFormBuilder()
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false
            ])
            ->add('publishedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'constraints' => [
                    new LessThan([
                        'value' => new \DateTime(), // Utilisez la date actuelle comme valeur de comparaison
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
                'label' => 'Url de la vidéo',
                'required' => true,
                
            ])

            ->getForm();

        $form->handleRequest($request);

            //requete pour tester si la vidéo est valide
            //mise en forme de la requête 
            $lien = $form->get('VideoId')->getData();
            $url = "https://youtu.be/" . $lien;

            // Passage dans la fonction de validation 
            $test = $this->validateYoutubeURL($url);
            
            // Exploitation des résultats
            if ($test === 1) {
                $form->get('VideoId')->addError(new FormError('error'));
            }
            elseif ($test === 3) {
                
            }

        if ($form->isSubmitted() && $form->isValid()) {

            $formation = new Formation();
            $formation->setTitle($form->get('title')->getData());
            $formation->setDescription($form->get('description')->getData());
            $formation->setPublishedAt($form->get('publishedAt')->getData());
            
            
            //requete pour tester si la vidéo est valide
            //mise en forme de la requête 
            $lien = $form->get('VideoId')->getData();
            $url = "https://www.youtube.com/embed/" . $lien;
            

            dump(get_headers($url));die;



            $formation->setVideoId($form->get('VideoId')->getData());

            $categories = $form->get('categories')->getData();

            for ($i = 0; $i < count($categories); $i++) {
                $formation->addCategory($categories[$i]);
            }

            $nameOfPlaylist = $form->get('playlist')->getData();

            $playlist = $playlistRepository->findOneBy([
                'name' => $nameOfPlaylist->getName()
            ]);

            $formation->setPlaylist($playlist);

            $manager->persist($formation);

            $manager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('/admin/addform.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function validateYoutubeURL($url)
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', $url);

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            if (strpos($content, '<meta name="title" content="">') !== false) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 3;
        }
    }
}
