<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Helper\TypeConverter;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CommentController extends AbstractController
{
    private Response $response;

    private Request $request;

    public function __construct() {
        $this->response = new Response();
        $this->response->headers->set('Content-Type', 'application/json');
    }

    private function getParams(): array
    {
        return json_decode($this->request->getContent(), true);;
    }

    private function findById($id, ManagerRegistry $doctrine): object
    {
        $repository = $doctrine->getRepository(Comment::class)->find($id);
        if (!$repository) {
            throw $this->createNotFoundException(
                'No comment found for id '.$id
            );
        }
        return $repository;
    }

    /**
     * Action to create a new comment
     *
     * @param $postId
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function create($postId, Request $request, ManagerRegistry $doctrine): Response
    {
        $post = (new PostController())->findById($postId,$doctrine);
        $this->request = $request;
        $entityManager = $doctrine->getManager();
        $comment = new Comment();
        $params = $this->getParams();
        $comment->setContent($params['content']);
        $comment->setAuthorName($params['author_name']);
        $comment->setPostId($post->getId());
        $entityManager->persist($comment);
        $entityManager->flush();
        return new Response('Saved new comment with id ' . $comment->getId());
    }

    /**
     * Action to show comment by ID from DB
     *
     * @param $id
     * @param $postId
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function showOne($id, $postId, ManagerRegistry $doctrine): object
    {
        $repository = $doctrine->getRepository(Comment::class)->findBy(array('id'=>$id,'post_id'=>$postId));
        $jsonContent = TypeConverter::objectToJson($repository);
        return $this->response->setContent($jsonContent);
    }

    /**
     * Action to show all comments from DB
     *
     * @param $postId
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function showAll($postId, ManagerRegistry $doctrine):object
    {
        $repository = $doctrine->getRepository(Comment::class)->findBy(array('post_id'=>$postId));
        $jsonContent = TypeConverter::objectToJson($repository);
        return $this->response->setContent($jsonContent);
    }

    /**
     * Action to update a comment by id (Method: PUT)
     *
     * @param ManagerRegistry $doctrine
     * @param $postId
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function update(ManagerRegistry $doctrine, $postId, int $id, Request $request): Response
    {
        (new PostController())->findById($postId,$doctrine);
        $this->request = $request;
        $entityManager = $doctrine->getManager();
        $params = $this->getParams();
        $comment = $this->findById($id,$doctrine);
        $comment->setContent($params['content']);
        $comment->setAuthorName($params['author_name']);
        $entityManager->flush();
        return $this->response->setContent("Comment with id ". $comment->getId() . "updated");
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
        $comment = $this->findById($id,$doctrine);
        $entityManager->remove($comment);
        $entityManager->flush();
        return $this->response->setContent("Comment deleted");
    }
}