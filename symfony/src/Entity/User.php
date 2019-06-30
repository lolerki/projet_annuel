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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user_get_collection","user_post_collection","user_put_item"})
     */
    private $firstName;

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

    public function __construct()
    {
        $this->createAt = new \DateTime('now');
        $this->events = new ArrayCollection();
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
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

    public function getFacebookId(): string
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
     * Generates the magic method
     *
     */
    public function __toString(){
        // to show the name of the Category in the select
        //return $this->firstName;
        if(is_null($this->firstName)) {
            return 'NULL';
        }
        return $this->firstName;

        // to show the id of the Category in the select
        // return $this->id;
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

}
