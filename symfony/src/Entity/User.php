<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Rollerworks\Component\PasswordStrength\Validator\Constraints as RollerworksPassword;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user_account")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ApiResource(
 *     collectionOperations={
 *           "get"={
 *              "normalization_context"={"groups"={"user_get_collection"}}
 *          },
 *          "post"={
 *             "method"="POST",
 *             "normalization_context"={"groups"={"user_post_collection"}},
 *             "access_control"="is_granted('ROLE_ADMIN')"
 *          }
 *     },
 *     itemOperations={
 *           "get"={
 *             "method"="GET",
 *             "normalization_context"={"groups"={"user_get_item"}}
 *            },
 *           "put"={
 *             "method"="PUT",
 *             "normalization_context"={"groups"={"user_put_item"}},
 *             "access_control"="is_granted('ROLE_ADMIN')"
 *           },
 *           "delete"={
 *             "method"="DELETE",
 *             "normalization_context"={"groups"={"user_delete_item"}},
 *             "access_control"="is_granted('ROLE_ADMIN')"
 *          }
 *     }
 *    )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Groups({"user_get_collection","user_post_collection","user_get_item","user_put_item", "profile_get_collection", "event_get_item", "event_post_collection"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user_get_collection","user_post_collection","user_get_item","user_put_item"})
     */
    private $roles = [];

    /**
     * @RollerworksPassword\PasswordRequirements(requireLetters=true, requireNumbers=true, requireCaseDiff=true, minLength=6)
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"user_post_collection","user_put_item"})
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="datetime")
     * @Groups({"user_get_collection","user_post_collection"})
     */
    private $createAt;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"user_post_collection","user_put_item"})
     */
    private $facebookId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="idUser")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Like", mappedBy="idUser")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ParticipationEvent", mappedBy="idUser")
     */
    private $participationEvents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="idUser")
     */
    private $comments;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Profile", mappedBy="idUser", cascade={"persist", "remove"})
     */
    private $profile;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notify", mappedBy="idUser")
     */
    private $notifies;

    public function __construct()
    {
        $this->createAt = new \DateTime('now');
        $this->events = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->participationEvents = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->notifies = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getEmail();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setIdUser($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getIdUser() === $this) {
                $event->setIdUser(null);
            }
        }

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
            $like->setIdUser($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getIdUser() === $this) {
                $like->setIdUser(null);
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
            $participationEvent->setIdUser($this);
        }

        return $this;
    }

    public function removeParticipationEvent(ParticipationEvent $participationEvent): self
    {
        if ($this->participationEvents->contains($participationEvent)) {
            $this->participationEvents->removeElement($participationEvent);
            // set the owning side to null (unless already changed)
            if ($participationEvent->getIdUser() === $this) {
                $participationEvent->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setIdUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getIdUser() === $this) {
                $comment->setIdUser(null);
            }
        }

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): self
    {
        $this->profile = $profile;

        // set the owning side of the relation if necessary
        if ($this !== $profile->getIdUser()) {
            $profile->setIdUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Notify[]
     */
    public function getNotifies(): Collection
    {
        return $this->notifies;
    }

    public function addNotify(Notify $notify): self
    {
        if (!$this->notifies->contains($notify)) {
            $this->notifies[] = $notify;
            $notify->setIdUser($this);
        }

        return $this;
    }

    public function removeNotify(Notify $notify): self
    {
        if ($this->notifies->contains($notify)) {
            $this->notifies->removeElement($notify);
            // set the owning side to null (unless already changed)
            if ($notify->getIdUser() === $this) {
                $notify->setIdUser(null);
            }
        }

        return $this;
    }

}
