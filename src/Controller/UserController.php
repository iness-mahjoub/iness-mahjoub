<?php

namespace App\Controller;

use App\Entity\Users;

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
class UserController extends AbstractController
{
    #[Route('/api/users', name: 'categories', methods: ['GET'])]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $users = $this->getDoctrine()->getRepository(Users::class)->findAll();
        $data = $serializer->serialize($users, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/users/{id}', name: 'categories', methods: ['GET'])]
    public function show(Users $users, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($users, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/users', name: 'users', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $users = new Users();
        $users->setFname($data['fname'] ?? null);
        $users->setLname($data['lname'] ?? null);
        $users->setEmail($data['email'] ?? null);
        $users->setNumTel($data['numtel'] ?? null);


        $errors = $validator->validate($users);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($users);
        $entityManager->flush();

        $data = $serializer->serialize($users, 'json');

        return new JsonResponse($data, Response::HTTP_CREATED, ['Location' => '/api/products/' . $users->getId()], true);
    }

    #[Route('/api/users/{id}', name: 'users', methods: ['PUT'])]
    public function update(Panier $panier, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $panier->setQtt($data['name'] ?? $panier->getQtt());

        $errors = $validator->validate($panier);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($panier);
        $entityManager->flush();

        $data = $serializer->serialize($panier, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/users/{id}', name: 'users', methods: ['DELETE'])]
    public function delete(Users $users): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($users);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

