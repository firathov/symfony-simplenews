<?php

namespace App\Controller;

use App\Entity\Post;
use App\Helper\TypeConverter;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends AbstractController
{
    private Response $response;

    private Request $request;

    public function __construct() {
        $this->response = new Response();
        $this->response->headers->set('Content-Type', 'application/json');
    }

    /**
     * Function to get whole request from Front-side
     *
     * @return array
     */
    private function getParams(): array
    {
        return json_decode($this->request->getContent(), true);
    }

    /**
     * Function to get post by ID
     *
     * @param $id
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function findById($id, ManagerRegistry $doctrine): object
    {
        $repository = $doctrine->getRepository(Post::class)->find($id);
        if (!$repository) {
            throw $this->createNotFoundException(
                'No post found for id '.$id
            );
        }
        return $repository;
    }

    /**
     * Action to create a new post (Method: POST)
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->request = $request;
        $entityManager = $doctrine->getManager();
        $post = new Post();
        $params = $this->getParams();
        $post->setTitle($params['title']);
        $post->setLink($params['link']);
        $post->setAuthorName($params['author_name']);
        $entityManager->persist($post);
        $entityManager->flush();
        return $this->response->setContent('Saved new post with id ' . $post->getId());
    }

    /**
     * Action to show post by id (Method: GET)
     *
     * @param ManagerRegistry $doctrine
     * @param int $id
     * @return Response
     */
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $post = $this->findById($id,$doctrine);
        $jsonContent = TypeConverter::objectToJson($post);
        return $this->response->setContent($jsonContent);
    }

    /**
     * Action to show all posts in DB (Method: GET)
     *
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function showAll(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Post::class);
        $result = $repository->findAll();
        $jsonContent = TypeConverter::objectToJson($result);
        return $this->response->setContent($jsonContent);
    }

    /**
     * Action to update a post by I'd (Method: PUT)
     *
     * @param ManagerRegistry $doctrine
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function update(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $this->request = $request;
        $entityManager = $doctrine->getManager();
        $params = $this->getParams();
        $post = $this->findById($id,$doctrine);
        $post->setTitle($params['title']);
        $post->setLink($params['link']);
        $post->setAuthorName($params['author_name']);
        $entityManager->flush();
        return $this->response->setContent("Updated post with id ". $post->getId());
    }

    /**
     * Action to delete a post by id (Method: DELETE)
     *
     * @param ManagerRegistry $doctrine
     * @param int $id
     * @return Response
     */
    public function remove(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $post = $this->findById($id,$doctrine);
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->response->setContent("Post deleted");
    }

    /**
     * Action to update a post's comment by id (Method: POST)
     *
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @return Response
     */
    public function upvotePost(int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $this->request = $request;
        $entityManager = $doctrine->getManager();
        $params = $this->getParams();
        $post = $this->findById($id,$doctrine);
        $post->setAmountOfUpvotes($post->getAmountOfUpvotes() + $params['point']);
        $entityManager->flush();
        return $this->response->setContent("You rated post with id ". $post->getId());
    }
}