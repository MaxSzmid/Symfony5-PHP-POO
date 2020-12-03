<?php

namespace App\Controller;

use App\Entity\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyActivityController extends AbstractController
{
    /**
     * @Route("/my_activity", name="my_activity")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $myActivityPost = $em->getRepository(Comments::class)->findCommentActivity($user->getId());
        return $this->render('my_activity/index.html.twig', [
            'activity' =>  $myActivityPost,
        ]);
    }
}
