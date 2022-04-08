<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PostController extends AbstractController
{
    /**
     * Action to create a new post
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $post = new Post();
        $post->setTitle($request->get('title'));
        $post->setLink($request->get('link'));
        $post->setAuthorName($request->get('author-name'));
        $entityManager->persist($post);
        $entityManager->flush();
        return new Response('Saved new post with id ' . $post->getId());
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
        $post = $doctrine->getRepository(Post::class)->find($id);
        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        $data = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'link' => $post->getLink(),
            'author-name' => $post->getAuthorName(),
            'creation-date' => $post->getCreationDate(),
            'amount-upvotes' => $post->getAmountofUpvotes()
        ];
        return new Response("ID - " . $data['id'] . "<br>Title - " . $data['title'] . "<br>Link - " .
            $data['link'] . "<br>Author Name - " . $data['author-name'] . "<br>Creation Date - " .
            $data['creation-date'] . "<br>Amount of Upvotes - " . $data['amount-upvotes']);
    }

    /**
     * Action to show all posts in DB
     *
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function showAll(ManagerRegistry $doctrine): Response
    {
        $encoder = [new JsonEncode()];
        $normalizer = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizer, $encoder);
        $repository = $doctrine->getRepository(Post::class);
        $result = $repository->findAll();
        $jsonContent = $serializer->serialize($result, 'json');
        return new Response($jsonContent);
    }

    /**
     * Action to update a post by I'd (Method: POST)
     *
     * @param ManagerRegistry $doctrine
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function update(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);
        if (!$post) {
            throw $this->createNotFoundException(
                'No post found for id ' . $id
            );
        }
        $data = [
            'title' => $request->get('title'),
            'link' => $request->get('link'),
            'author-name' => $request->get('author-name'),
            'amount-upvotes' => $request->get('amount-upvotes')
        ];

        if (!isset($data['title']) || !isset($data['link']) || !isset($data['author-name'])
            || !isset($data['amount-upvotes'])) {
            return new Response("Invalid Input");
        }
        $post->setTitle($data['title']);
        $post->setLink($data['link']);
        $post->setAuthorName($data['author-name']);
        $post->setAmountOfUpvotes($data['amount-upvotes']);
        $entityManager->persist($post);
        $entityManager->flush();

        return new Response('Updated new post with id ' . $post->getId());
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
        $post = $entityManager->getRepository(Post::class)->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        $entityManager->remove($post);
        $entityManager->flush();

        return new Response('Post has been deleted');
    }
}