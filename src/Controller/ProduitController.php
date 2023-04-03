<?php

// src/Controller/ProduitController.php

namespace App\Controller;

use App\Entity\Produit;

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
class ProduitController extends AbstractController
{
    #[Route('/api/produits', name: 'produits', methods: ['GET'])]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $products = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        $data = $serializer->serialize($products, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/produits/{id}', name: 'produits', methods: ['GET'])]
    public function show(Produit $product, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($product, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/produits{id}', name: 'produits', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $product = new Produit();
        $product->setName($data['name'] ?? null);
        $product->setPrix($data['description'] ?? null);
        $product->setImage($data['image'] ?? null);

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        $data = $serializer->serialize($product, 'json');

        return new JsonResponse($data, Response::HTTP_CREATED, ['Location' => '/api/products/' . $product->getId()], true);
    }

    #[Route('/api/produits/{id}', name: 'produits', methods: ['PUT'])]
    public function update(Produit $product, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $product->setName($data['name'] ?? $product->getName());
        $product->setPrix($data['prix'] ?? $product->getPrix());
        $product->setImage($data['image'] ?? $product->getImage());

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        $data = $serializer->serialize($product, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/produits/{id}', name: 'produits', methods: ['DELETE'])]
    public function delete(Produit $product): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
