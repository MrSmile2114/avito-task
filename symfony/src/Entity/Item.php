<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Item
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     *
     * @ORM\Column(type="text")
     */
    private $imgLinks;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    public function __construct()
    {
        $this->created = new \DateTime();
    }

    public function getMainImgLink(): ?string
    {
        return $this->getImgLinksArr()[0];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImgLinksArr(): ?array
    {
        return explode(',', $this->imgLinks);
    }

    public function setImgLinksArr(array $imgLinks): self
    {
        $this->imgLinks = implode(',', $imgLinks);

        return $this;
    }

    public function getImgLinks(): ?string
    {
        return $this->imgLinks;
    }

    public function setImgLinks(string $imgLinks): self
    {
        $this->imgLinks = $imgLinks;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }
}
