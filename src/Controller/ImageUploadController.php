<?php

namespace App\Controller;

use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;

class ImageUploadController
{
    public function __invoke(Request $request): Categorie
    {
        $imageFile = $request->files->get('imageFile');
        $imageName = $imageFile->getClientOriginalName();
        $imageFile->move('images/categorie', $imageName);

        $categorie = new Categorie();
        $categorie->setName($request->request->get('name'));
        $categorie->setImage('/images/categorie/' . $imageName);
        $categorie->setUpdatedAt(new \DateTimeImmutable());

        // Persist the $categorie entity to the database using your EntityManager
        // $entityManager->persist($categorie);
        // $entityManager->flush();

        return $categorie;
    }
}
