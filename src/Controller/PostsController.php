<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Form\CommentType;
use App\Form\PostsType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
                $newFileName = $safeFileName . "-" . uniqid() . "." . $uploadedPDF->guessExtension();


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
                $post->setfecha_publicacion($post->generateDate());
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
    public function verPost($id, Request $request)
    {
        $comment = new Comments();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $postQueryResults = $em->getRepository(Posts::class)->getPost($id);
        $currentPost = $em->getRepository(Posts::class)->find($id);
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            //save new comment
            $idPost = array(
                'id' => $id
            );
            // GetPost devuelve un array, esta devuelvo un objeto.
            $comment->setComment($formComment->get('comment')->getData());
            $comment->setPosts($currentPost);
            $comment->setUser($this->getUser());
            $comment->setDatePublication($comment->generateDate());
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('verPost', $idPost);
        }
        // Find all comments
        $allComments = $em->getRepository(Comments::class)->findAllPosts($id);
        return $this->render('posts/verPost.html.twig', [
            'post' => $postQueryResults,
            'formComment' => $formComment->createView(),
            'allComments' => $allComments,
        ]);
    }

    /**
     * @Route("/myPosts", name="myPosts")
     */
    public function misPosts()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $posts = $em->getRepository(Posts::class)->findBy(['user' => $user]);
        return $this->render('posts/misPosts.html.twig', ['userPosts' => $posts]);
    }

    /**
     * @Route("/likes", name="likes")
     */
    public function likes(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $id = $request->request->get('id');
            $user = $this->getUser();
            $post = $em->getRepository(Posts::class)->find($id);
            $likes = $post->getLikes();
            $likes .= $user->getId() . ",";
            $post->setLikes($likes);
            $statusMessage = "";
            try {
                $post->setLikes($likes);
                $em->flush();
                $statusMessage = '<p class="text-success like-text"><i id="likeColorButton" class="material-icons" style="color:green;">thumb_up_alt</i> 
                You liked it. <br>Thanks for that! I hope you enjoyed the post.</p>';
            } catch (\Throwable $th) {
                $statusMessage = '<div class="alert alert-danger">
                <p class="text-dark">
                    We cant save your like please reload this page and try again, if the problem persist, contact suport...</p>
            </div>';
            }
            return new Response($statusMessage);
        }
    }
}
