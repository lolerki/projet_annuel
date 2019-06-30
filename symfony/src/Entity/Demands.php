<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DemandsRepository")
 */
class Demands
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numref;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datedemand;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TypeOfCustomer", inversedBy="demands")
     */
    private $typeofcustomer;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $linkgg;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datedemanded;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function __construct()
    {
        $this->typeofcustomer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumref(): ?string
    {
        return $this->numref;
    }

    public function setNumref(string $numref): self
    {
        $this->numref = $numref;

        return $this;
    }

    public function getDatedemand(): ?\DateTimeInterface
    {
        return $this->datedemand;
    }

    public function setDatedemand(\DateTimeInterface $datedemand): self
    {
        $this->datedemand = $datedemand;

        return $this;
    }

    /**
     * @return Collection|TypeOfCustomer[]
     */
    public function getTypeofcustomer(): Collection
    {
        return $this->typeofcustomer;
    }

    public function addTypeofcustomer(TypeOfCustomer $typeofcustomer): self
    {
        if (!$this->typeofcustomer->contains($typeofcustomer)) {
            $this->typeofcustomer[] = $typeofcustomer;
        }

        return $this;
    }

    public function removeTypeofcustomer(TypeOfCustomer $typeofcustomer): self
    {
        if ($this->typeofcustomer->contains($typeofcustomer)) {
            $this->typeofcustomer->removeElement($typeofcustomer);
        }

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getLinkgg(): ?string
    {
        return $this->linkgg;
    }

    public function setLinkgg(string $linkgg): self
    {
        $this->linkgg = $linkgg;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDatedemanded(): ?\DateTimeInterface
    {
        return $this->datedemanded;
    }

    public function setDatedemanded(\DateTimeInterface $datedemanded): self
    {
        $this->datedemanded = $datedemanded;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
