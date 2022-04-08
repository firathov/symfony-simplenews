<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

    /**
     * Action to show all comments from DB
     *
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function showAll(ManagerRegistry $doctrine):Response
    {
        $encoder = [new JsonEncode()];
        $normalizer = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizer, $encoder);
        $repository = $doctrine->getRepository(Comment::class);
        $result = $repository->findAll();
        $jsonContent = $serializer->serialize($result, 'json');
        return new Response($jsonContent);
    }

    /**
     * Action to update a comment by I'd (Method: POST)
     *
     * @param ManagerRegistry $doctrine
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function update(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $comment = $entityManager->getRepository(Comment::class)->find($id);
        if (!$comment) {
            throw $this->createNotFoundException(
                'No post found for id ' . $id
            );
        }

        $data = $request->get('content');

        if (!isset($data)) {
            return new Response("Invalid Input");
        }
        $comment->setContent($data);
        $entityManager->persist($comment);
        $entityManager->flush();

        return new Response('Comment with id ' . $comment->getId() . ' updated');
    }

    /**
     * Action to delete a comment by id (Method: DELETE)
     *
     * @param ManagerRegistry $doctrine
     * @param int $id
     * @return Response
     */
    public function remove(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $comment = $entityManager->getRepository(Comment::class)->find($id);

        if (!$comment) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        $entityManager->remove($comment);
        $entityManager->flush();

        return new Response('Comment has been deleted');
    }
}