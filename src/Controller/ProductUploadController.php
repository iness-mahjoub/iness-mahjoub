<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\Detail;
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
        $imageFile = $request->files->get('imageFile');
        $imageName = $imageFile->getClientOriginalName();
        $imageFile->move('images/produits', $imageName);

        $produit->setImage('/images/produits/' . $imageName);
        $produit->setName($request->request->get('name'));
        $produit->setPrix($request->request->get('prix'));
        $produit->setUpdatedAt(new \DateTimeImmutable());

        $categorieId = $request->request->get('categorie_id');
        if ($categorieId) {
            // Fetch the Categorie entity from your database based on the $categorieId value
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($categorieId);
            $produit->setCategorie($categorie);
        }

        $detail = new Detail();
        $detail->setDescription($request->request->get('description'));
        $detail->setSize($request->request->get('size'));
        $detail->setStock($request->request->get('stock'));
        $detail->setStock1((int)$request->request->get('stock1'));

        $produit->addDetail($detail);

        $this->entityManager->persist($produit);
        $this->entityManager->persist($detail);
        $this->entityManager->flush();

        return $produit;
    }
}
