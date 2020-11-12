<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*creo un manejador de entidades, mas adelante me va a permitir mantener una entidad (objeto) en memoria*/
            $entityManager = $this->getDoctrine()->getManager();
           
            /*Symfony tiene 2 roles por defecto ROLE_USER y ROLE_ADMIN, uso el metodo set de mi objeto para asignarle el rol*/
            //$user->setRole(['ROLE_USER']);

            /*Asigno si esta baneado, por defecto no esta*/
            //$user->setBanned(false);
            
            /*Enctripto la contraseña utilizando symfony password encoder*/ 
            /*Uso el metodo setPassword de mi objeto, en los parametros uso el objeto  de la clase UserPasswordEncoderInterface con su correspondiente metodo
            para encriptar, los 2 parametros que hay que pasarle son: el tipo de*/
            $user->setPassword($passwordEncoder->encodePassword($user, $form['password']->getData()));
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', User::USER_REGISTERED);
            return $this->redirectToRoute('register');
        }
        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'form' => $form->createView(),
        ]);
    }
}
