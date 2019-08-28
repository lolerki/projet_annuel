<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     collectionOperations={
 *           "get"={
 *              "normalization_context"={"groups"={"event_get_collection"}}
 *          },
 *          "post"={
 *             "method"="POST",
 *             "normalization_context"={"groups"={"event_post_collection"}},
 *             "access_control"="is_granted('ROLE_ADMIN')"
 *          }
 *     },
 *     itemOperations={
 *           "get"={
 *             "method"="GET",
 *             "normalization_context"={"groups"={"event_get_item"}}
 *            },
 *           "put"={
 *             "method"="PUT",
 *             "normalization_context"={"groups"={"event_put_item"}},
 *             "access_control"="is_granted('ROLE_ADMIN')"
 *           },
 *           "delete"={
 *             "method"="DELETE",
 *             "normalization_context"={"groups"={"event_delete_item"}},
 *             "access_control"="is_granted('ROLE_ADMIN')"
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event_get_collection","event_post_collection","event_get_item","event_put_item", "user_get_item"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"event_get_collection","event_post_collection","event_get_item","event_put_item"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"event_get_collection","event_post_collection","event_get_item"})
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"event_get_collection","event_post_collection","event_get_item","event_put_item", "user_get_item"})
     */
    private $dateEvent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"event_get_collection","event_post_collection","event_get_item","event_put_item"})
     */
    private $linkGoogle;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"event_get_collection","event_post_collection","event_get_item","event_put_item"})
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="events")
     * @Groups({"event_post_collection","event_get_item", "event_get_collection"})
     */
    private $idUser;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Like", mappedBy="idEvent")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="idEvent")
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ParticipationEvent", mappedBy="idEvent")
     */
    private $participationEvents;

    /**
     * @ORM\Column(type="string")
     */
    private $statut;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->participationEvents = new ArrayCollection();
        $this->createAt = new \DateTime('now');
        $this->statut = '1';
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTimeInterface $dateEvent): self
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getLinkGoogle(): ?string
    {
        return $this->linkGoogle;
    }

    public function setLinkGoogle(?string $linkGoogle): self
    {
        $this->linkGoogle = $linkGoogle;

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

    public function getIdUser(): ?user
    {
        return $this->idUser;
    }

    public function setIdUser(?user $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setIdEvent($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getIdEvent() === $this) {
                $like->setIdEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setIdEvent($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getIdEvent() === $this) {
                $note->setIdEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ParticipationEvent[]
     */
    public function getParticipationEvents(): Collection
    {
        return $this->participationEvents;
    }

    public function addParticipationEvent(ParticipationEvent $participationEvent): self
    {
        if (!$this->participationEvents->contains($participationEvent)) {
            $this->participationEvents[] = $participationEvent;
            $participationEvent->setIdEvent($this);
        }

        return $this;
    }

    public function removeParticipationEvent(ParticipationEvent $participationEvent): self
    {
        if ($this->participationEvents->contains($participationEvent)) {
            $this->participationEvents->removeElement($participationEvent);
            // set the owning side to null (unless already changed)
            if ($participationEvent->getIdEvent() === $this) {
                $participationEvent->setIdEvent(null);
            }
        }

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(int $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

}
