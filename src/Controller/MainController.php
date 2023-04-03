<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class MainController extends AbstractController
{
    public function register(Request $request, \Swift_Mailer $mailer, UserPasswordEncoderInterface $encoder, JWTTokenManagerInterface $JWTManager)
    {
        $em = $this->getDoctrine()->getManager();

        $email = $request->request->get('_email');
        $password = $request->request->get('_password');

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($encoder->encodePassword($user, $password));
        $em->persist($user);
        $em->flush();

        $message = (new \Swift_Message('Inscription E-Corp'))
            ->setFrom('admin@ecorp.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/registration.html.twig',
                    array(
                        'token' =>  $JWTManager->create($user)
                    )
                ),
                'text/html'
            );

        $mailer->send($message);

        return new Response(sprintf('User %s successfully created', $user->getEmail()));
    }

    public function validate(Request $request, UserRepository $userRepository, JWTEncoderInterface $jwtEncoder, JWTTokenManagerInterface $JWTManager)
    {
        $jwtToken = $request->request->get('token');
        var_dump($jwtToken);
        $checkToken = $jwtEncoder->decode($jwtToken);

        $em = $this->getDoctrine()->getManager();
        $user = new User;
        $user = $userRepository->findOneBy(
            ['email' => $checkToken['username']]
        );
        $user->setRoles(['ROLE_USER']);
        $em->persist($user);
        $em->flush();

        return new Response(sprintf('User %s confirmed', $user->getEmail()));

    }
}