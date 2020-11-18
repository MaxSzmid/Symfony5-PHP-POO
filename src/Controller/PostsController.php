<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PostsController extends AbstractController
{
    /**
     * @Route("/posts", name="posts")
     */
    public function index(Request $request, SluggerInterface $slugger)
    {

        $post = new Posts();
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedPDF = $form->get('foto')->getData();
            //if the file uploaded is not required, we need this condition
            if ($uploadedPDF) {
                $originalFileName = pathinfo($uploadedPDF->getClientOriginalName(), PATHINFO_FILENAME);
                //incluyo el nombre del archivo a la ruta de la URL
                // slugger crea una url en base al nombre del arichivo original
                $safeFileName = $slugger->slug($originalFileName);
                //uniqid genera un id unico, guessExtension pregunta la extension del archivo que se subio
                $newFileName = $safeFileName . "-" . uniqid() . "-" . $uploadedPDF->guessExtension();


                try {
                    //va a intentar subir o mover el archivo al directorio seleccionado "brochures_directory"
                    $uploadedPDF->move(
                        //brochures_directory esta seteado en los servicios con una ruta por defecto 
                        /** @config/service */
                        $this->getParameter('brochures_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {
                    throw new Exception("Algo salio mal y exploto todo");
                }
                $post->setFoto($newFileName);
            }
            $user = $this->getUser();
            $post->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('posts/index.html.twig', [
            'controller_name' => 'PostsController',
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/verPost/{id}", name="verPost")
     */
    public function verPost($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Posts::class)->getPost($id);
        return $this->render('posts/verPost.html.twig', ['post' => $post]);
    }

    /**
     * @Route("/misPosts", name="misPosts")
     */
    public function misPosts()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $posts = $em->getRepository(Posts::class)->findBy(['user' => $user]);
        return $this->render('posts/misPosts.html.twig', ['userPosts' => $posts]);
    }
}
