<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private $id;

    #[ORM\Column(name: 'title',type: 'string', length: 255)]
    private $title;

    #[ORM\Column(name: 'link', type: 'string', length: 255)]
    private $link;

    #[ORM\Column(name: 'amount_of_upvotes', type: 'integer')]
    private $amount_of_upvotes;

    #[ORM\Column(name: 'author_name', type: 'string', length: 45)]
    private $author_name;

    #[ORM\Column(name: 'creation_date', type: 'datetime', nullable: true)]
    #[Gedmo\Timestampable(on: 'create')]
    private $creation_date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getAmountOfUpvotes(): ?int
    {
        return $this->amount_of_upvotes;
    }

    public function setAmountOfUpvotes(int $amount_of_upvotes): self
    {
        $this->amount_of_upvotes = $amount_of_upvotes;

        return $this;
    }

    public function getAuthorName(): ?string
    {
        return $this->author_name;
    }

    public function setAuthorName(string $author_name): self
    {
        $this->author_name = $author_name;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(?\DateTimeInterface $creation_date): DateTime
    {
        $this->creation_date = new DateTime();

        return $this->creation_date;
    }
}
