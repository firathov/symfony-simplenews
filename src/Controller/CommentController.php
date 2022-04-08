<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends AbstractController
{
    /**
     * Action to create a new comment
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $comment = new Comment();
        $comment->setAuthorName($request->get('author-name'));
        $comment->setContent($request->get('content'));
        $entityManager->persist($comment);
        $entityManager->flush();
        return new Response('Saved new comment with id ' . $comment->getId());
    }
}