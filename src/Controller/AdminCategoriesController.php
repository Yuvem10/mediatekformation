<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Form\FormationType;
use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminCategoriesController extends AbstractController
{
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    public function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository = $categorieRepository;
    }
    

    /**
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/categories.html.twig', [
            "categories" => $categories
        ]);
    }

    /**
     * @Route("/admin/categories/duplicated", name="admin.categories.duplicated")
     * @return Response
     */
    public function dupliquee(): Response
    {
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/categories.html.twig', [
            "categories" => $categories,
            'error' => 'Ce nom de catégorie est déjà utilisé'
        ]);
    }

     /**
     * @Route("/admin/environnement/suppr/{id}", name="admin.categorie.suppr")
     * @param Categorie $categorie
     * @return Response
     */
    public function suppr(Categorie $categorie): Response{
        $this->categorieRepository->remove($categorie, true);
        return $this->redirectToRoute('admin.categories');
    }
    /**
     * @Route("/admin/categories/ajout", name="admin.categories.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        if ($this->isCsrfTokenValid('add', $request->get("token"))) {
            $ret = $this->redirectToRoute('admin.categories');

            $categorieName = $request->get("name");
            $repository = $this->categorieRepository->findAll();

            $duplicateFound = false;

            foreach ($repository as $oneCategorie){
            $name = $oneCategorie->getName();
            if (trim(strtolower($categorieName)) == trim(strtolower($name))){
                    $ret = $this->redirectToRoute('admin.categories.duplicated');
                    $duplicateFound = true;
            }
            }

            if(!$duplicateFound)
            {
                $categorie = new Categorie();
                $categorie->setName($categorieName);
                $this->categorieRepository->add($categorie, true);
            }

            return $ret;       
        }   
        return $this->redirectToRoute('admin.categories');
    }
}
