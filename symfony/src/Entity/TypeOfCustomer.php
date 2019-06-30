<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeOfCustomerRepository")
 */
class TypeOfCustomer
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
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Demands", mappedBy="typeofcustomer")
     */
    private $demands;

    public function __construct()
    {
        $this->demands = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection|Demands[]
     */
    public function getDemands(): Collection
    {
        return $this->demands;
    }

    public function addDemand(Demands $demand): self
    {
        if (!$this->demands->contains($demand)) {
            $this->demands[] = $demand;
            $demand->addTypeofcustomer($this);
        }

        return $this;
    }

    public function removeDemand(Demands $demand): self
    {
        if ($this->demands->contains($demand)) {
            $this->demands->removeElement($demand);
            $demand->removeTypeofcustomer($this);
        }

        return $this;
    }
}
