<?php

namespace App\Controller;
use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SousCategorieUploadController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request): SousCategorie
    {
        $produit = new SousCategorie();
        $imageFile = $request->files->get('imageFile');
        $imageName = $imageFile->getClientOriginalName();
        $imageFile->move('images/sousCategories', $imageName);

        $produit->setImage('/images/sousCategories/' . $imageName);
        $produit->setName($request->request->get('name'));
        $produit->setUpdatedAt(new \DateTimeImmutable());






        $categorieId = $request->request->get('categorie_id');
        if ($categorieId) {
            // Fetch the Categorie entity from your database based on the $categorieId value
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($categorieId);
            $produit->setCategorie($categorie);
        }

        return $produit;
    }

}
