<?php

// src/Controller/ProductController.php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Offre;
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
class OffreController extends AbstractController
{
    #[Route('/api/offres', name: 'offres', methods: ['GET'])]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $offre = $this->getDoctrine()->getRepository(Offre::class)->findAll();
        $data = $serializer->serialize($offre , 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/offres/{id}', name: 'offres', methods: ['GET'])]
    public function show(Commande $offre, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($offre, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/offres', name: 'offre', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $offre = new Offre();
        $offre->setTauxReduction($data['reduction'] ?? null);
        $offre->setDateDebut($data['datedebut'] ?? null);
        $offre->setDateFin($data['detefin'] ?? null);



        $errors = $validator->validate($offre);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offre);
        $entityManager->flush();

        $data = $serializer->serialize($offre, 'json');

        return new JsonResponse($data, Response::HTTP_CREATED, ['Location' => '/api/products/' . $offre->getId()], true);
    }

    #[Route('/api/commandes/{id}', name: 'commande', methods: ['PUT'])]
    public function update(Offre $offre, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $offre->setTauxReduction($data['reduction'] ?? null);
        $offre->setDateDebut($data['datedebut'] ?? null);
        $offre->setDateFin($data['detefin'] ?? null);



        $errors = $validator->validate($offre);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offre);
        $entityManager->flush();

        $data = $serializer->serialize($offre, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
    #[Route('/api/offres/{id}', name: 'offres', methods: ['DELETE'])]
    public function delete(Offre $offre): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($offre);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
