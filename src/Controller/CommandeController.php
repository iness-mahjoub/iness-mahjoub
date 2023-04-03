<?php

// src/Controller/ProductController.php

namespace App\Controller;

use App\Entity\Commande;
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
class CommandeController extends AbstractController
{
    #[Route('/api/commandes', name: 'commande', methods: ['GET'])]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findAll();
        $data = $serializer->serialize($commande , 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/commandes/{id}', name: 'commande', methods: ['GET'])]
    public function show(Commande $commande, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($commande, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/commandes', name: 'commande', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $commande = new Commande();
        $commande->setDate($data['date'] ?? null);
        $commande->setStatutC($data['statut'] ?? null);
        $commande->setMontanTotal($data['montt'] ?? null);



        $errors = $validator->validate($commande);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commande);
        $entityManager->flush();

        $data = $serializer->serialize($commande, 'json');

        return new JsonResponse($data, Response::HTTP_CREATED, ['Location' => '/api/products/' . $commande->getId()], true);
    }

    #[Route('/api/commandes/{id}', name: 'commande', methods: ['PUT'])]
    public function update(Commande $commande, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $commande->setDate($data['date'] ?? null);
        $commande->setStatutC($data['statut'] ?? null);
        $commande->setMontanTotal($data['montt'] ?? null);


        $errors = $validator->validate($commande);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commande);
        $entityManager->flush();

        $data = $serializer->serialize($commande, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
    #[Route('/api/commandes/{id}', name: 'commande', methods: ['DELETE'])]
    public function delete(Commande $commande): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($commande);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
