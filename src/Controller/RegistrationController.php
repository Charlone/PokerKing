<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request): Response
    {
        $registration = new User();

        $form = $this->createForm(RegistrationType::class, $registration, [
            'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registration->setPassword($this->passwordEncoder->encodePassword($registration, $registration->getPassword()));
            $registration->setRoles(['ROLE_USER']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($registration);
            $em->flush();
            $em->getConnection()->close();

            $this->addFlash(
                'notice',
                'Congratulations you have successfully signed up!'
            );

// TODO route to logged in home landing page
            return $this->render('home/index.html.twig');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
