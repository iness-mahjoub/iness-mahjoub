<?php

// src/Controller/ProduitController.php

namespace App\Controller;

use App\Entity\Categorie;


use App\Entity\SousCategorie;
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
class SousCategorieController extends AbstractController
{
    #[Route('/api/sous_categories', name: 'sous_categories', methods: ['GET'])]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $souscategories = $this->getDoctrine()->getRepository(SousCategorie::class)->findAll();
        $data = $serializer->serialize($souscategories, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/categories/{id}', name: 'sous_cotegories', methods: ['GET'])]
    public function show(SousCategorie $souscategories, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($souscategories, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/sous_categories/{id}', name: 'sous_cotegories', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $souscategories = new SousCategorie();
        $souscategories->setName($data['name'] ?? null);
        $souscategories->setImage($data['image'] ?? null);

        $errors = $validator->validate($souscategories);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($souscategories);
        $entityManager->flush();

        $data = $serializer->serialize($souscategories, 'json');

        return new JsonResponse($data, Response::HTTP_CREATED, ['Location' => '/api/products/' . $souscategories->getId()], true);
    }

    #[Route('/api/sous_categories/{id}', name: 'sous_categories', methods: ['PUT'])]
    public function update(SousCategorie $souscategories, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $souscategories->setName($data['name'] ?? $souscategories->getName());

        $souscategories->setImage($data['image'] ?? $souscategories->getImage());

        $errors = $validator->validate($souscategories);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($souscategories);
        $entityManager->flush();

        $data = $serializer->serialize($souscategories, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/sous_categories/{id}', name: 'sous_categories', methods: ['DELETE'])]
    public function delete(SousCategorie $souscategories): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($souscategories);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
