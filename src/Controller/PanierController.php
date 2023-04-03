<?php



namespace App\Controller;

use App\Entity\Panier;

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
class PanierController extends AbstractController
{
    #[Route('/api/paniers', name: 'paniers', methods: ['GET'])]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $products = $this->getDoctrine()->getRepository(Panier::class)->findAll();
        $data = $serializer->serialize($products, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/paniers/{id}', name: 'paniers', methods: ['POST'])]
    public function show(Panier $product, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($product, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/paniers', name: 'paniers', methods: ['GET'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $panier = new Panier();
        $panier->setQtt($data['name'] ?? null);


        $errors = $validator->validate($panier);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($panier);
        $entityManager->flush();

        $data = $serializer->serialize($panier, 'json');

        return new JsonResponse($data, Response::HTTP_CREATED, ['Location' => '/api/products/' . $panier->getId()], true);
    }

    #[Route('/api/paniers/{id}', name: 'paniers', methods: ['PUT'])]
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

        #[Route('/api/paniers/{id}', name: 'paniers', methods: ['DELETE'])]
    public function delete(Panier $panier): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($panier);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
