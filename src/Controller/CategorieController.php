<?php

// src/Controller/ProduitController.php

namespace App\Controller;

use App\Entity\Categorie;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
/**
 * @method getDoctrine()
 */
class CategorieController extends AbstractController
{
    #[Route('/api/categories', name: 'categories', methods: ['GET'])]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $cotegories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $data = $serializer->serialize($cotegories, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/categories/{id}', name: 'cotegories', methods: ['GET'])]
    public function show(Categorie $categorie, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($categorie, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/categories/{id}', name: 'cotegories', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $categorie = new Categorie();
        $categorie->setName($data['name'] ?? null);
        $categorie->setImage($data['image'] ?? null);

        $errors = $validator->validate($categorie);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($categorie);
        $entityManager->flush();

        $data = $serializer->serialize($categorie, 'json');

        return new JsonResponse($data, Response::HTTP_CREATED, ['Location' => '/api/products/' . $categorie->getId()], true);
    }

    #[Route('/api/categories/{id}', name: 'categories', methods: ['PUT'])]
    public function update(Categorie $categorie, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $categorie->setName($data['name'] ?? $categorie->getName());

        $categorie->setImage($data['image'] ?? $categorie->getImage());

        $errors = $validator->validate($categorie);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($categorie);
        $entityManager->flush();

        $data = $serializer->serialize($categorie, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/categories/{id}', name: 'categories', methods: ['DELETE'])]
    public function delete(Categorie $categorie): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($categorie);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
