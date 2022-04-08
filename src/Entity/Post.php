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
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    private $link;

    #[ORM\Column(type: 'integer', nullable: true, options:["default"=>0])]
    private $amount_of_upvotes = 0;

    #[ORM\Column(type: 'string', length: 45)]
    private $author_name;

    #[ORM\Column(type: 'datetime', nullable: true, options:["default"=>"CURRENT_TIMESTAMP"])]
//    #[Gedmo\Timestampable(on: 'create')]
    private $creation_date;

    public function __construct()
    {
        $this->creation_date = new DateTime();
    }

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

    public function getCreationDate(): string
    {
        return $this->creation_date->format('Y-m-d H:i:s');
    }

    public function setCreationDate(?\DateTimeInterface $creation_date): DateTime
    {
        $this->creation_date = new DateTime();

        return $this->creation_date;
    }
}
