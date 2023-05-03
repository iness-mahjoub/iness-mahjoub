<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductUploadController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request): Produit
    {
        $produit = new Produit();
        $produit->setName($request->request->get('name'));
        $produit->setPrix($request->request->get('prix'));
        $imageFile = $request->files->get('imageFile');
        if ($imageFile) {
            $produit->setImageFile($imageFile);
            $produit->setImage($imageFile->getClientOriginalName());
        }

        $categorieId = $request->request->get('categorie_id');
        if ($categorieId) {
            // Fetch the Categorie entity from your database based on the $categorieId value
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($categorieId);
            $produit->setCategorie($categorie);
        }

        return $produit;
    }

}
