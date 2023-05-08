<?php

namespace App\Controller;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoriUpdateController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request, Categorie $categorie): Categorie
    {
        // Get the image file from the request
        $imageFile = $request->files->get('imageFile');
        if (!$imageFile) {
            throw new BadRequestHttpException('Image file is required');
        }

        // Validate and move the uploaded image file to the correct location
        if (!$imageFile->isValid()) {
            throw new BadRequestHttpException('Invalid image file');
        }
        $imageName = uniqid() . '.' . $imageFile->guessExtension();
        $imageFile->move('images/categorie', $imageName);

        // Update the Categorie entity with the new data
        $name = $request->request->get('name');
        if (!$name) {
            throw new BadRequestHttpException('Name is required');
        }
        $categorie->setName($name);
        $categorie->setImage('/images/categorie/' . $imageName);
        $categorie->setUpdatedAt(new \DateTimeImmutable());

        // Persist the updated $categorie entity to the database using your EntityManager
        $this->entityManager->flush();

        return $categorie;
    }
}
