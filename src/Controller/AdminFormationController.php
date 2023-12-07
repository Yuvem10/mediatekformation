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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


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

    public function ajout(Formation $formation = null, Request $request, EntityManagerInterface $manager, PlaylistRepository $playlistRepository): Response
    {
        if ($formation == null){
            $formation = new Formation;
        }

        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formation = new Formation();
            $formation->setTitle($form->get('title')->getData());
            $formation->setDescription($form->get('description')->getData());
            $formation->setPublishedAt($form->get('publishedAt')->getData());
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
   
}
