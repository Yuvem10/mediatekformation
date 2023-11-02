<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Form\FormationType;
use App\Form\PlaylistType;
use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminPlaylistController extends AbstractController
{
    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
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
    
    public function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * @Route("admin/playlists", name="admin.playlists")
     * @return Response
     */
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        $formations = $this->formationRepository->findAll();
        return $this->render("admin/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("admin/playlists/tri/{champ}/{ordre}", name="admin.playlists.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        switch($champ){
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "formations":
                $playlists = $this->playlistRepository->findAllOrderByFormations($ordre);
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,            
        ]);
    }          
	
    /**
     * @Route("admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
    /**
     * @Route("admin/playlists/playlist/{id}", name="admin.playlists.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("admin/playlist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);        
    }  

    /**
     * @Route("/admin/playlists/suppr/{id}", name="admin.playlists.suppr")
     * @param int $id
     * @param Playlist $playlist
     * @return Response
     */
    public function suppr(int $id) : Response {
        $playlist = $this->playlistRepository->find($id);
        
        $this->playlistRepository->remove($playlist, true);
        return $this->redirectToRoute('admin.playlists');
    }

     /**
     * @Route("/admin/playlists/edit/{id}", name="admin.playlists.edit")
     * @param playlist $playlist
     * @return Response
     */
    public function edit(playlist $playlist, Request $request, $id): Response {
        
        $formplaylist = $this->createForm(PlaylistType::class, $playlist);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);

        $formplaylist->handleRequest($request);
        if ($formplaylist->isSubmitted()&& $formplaylist->isValid()){
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute('admin.playlists');
        }
        
        return $this->render("/admin/playlist.edit.html.twig", [
            'playlist' => $playlist,
            'form' => $formplaylist->createView(),
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
           
        ]);
    }

    /**
     * @Route("/admin/playlist/ajout", name="admin.playlist.ajout")
     */

     public function ajout(Request $request, EntityManagerInterface $manager, PlaylistRepository $playlistRepository) : Response {
        $form = $this->createFormBuilder()
        ->add('name', TextType::class, [
            'label'=> 'Titre de la playlist',
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false
        ])
        ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $playlist = new playlist();
            $playlist->setName($form->get('name')->getData());
            $playlist->setDescription($form->get('description')->getData());
           
            $manager->persist($playlist);

            $manager->flush();

            return $this->redirectToRoute('admin.playlists');
        }
        
        return $this->render('/admin/addplaylist.html.twig', [
            'form' => $form->createView()
        ]);
     }
}
