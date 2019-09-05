<?php

namespace App\Entity;

use Serializable;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     collectionOperations={
 *           "get"={
 *              "normalization_context"={"groups"={"profile_get_collection"}}
 *          },
 *          "post"={
 *             "method"="POST",
 *             "normalization_context"={"groups"={"profile_post_collection"}},
 *             "access_control"="is_granted('ROLE_ADMIN')"
 *          }
 *     },
 *     itemOperations={
 *           "get"={
 *             "method"="GET",
 *             "normalization_context"={"groups"={"profile_get_item"}}
 *            },
 *           "put"={
 *             "method"="PUT",
 *             "normalization_context"={"groups"={"profile_put_item"}},
 *             "access_control"="is_granted('ROLE_ADMIN')"
 *           },
 *           "delete"={
 *             "method"="DELETE",
 *             "normalization_context"={"groups"={"profile_delete_item"}},
 *             "access_control"="is_granted('ROLE_ADMIN')"
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 * @Vich\Uploadable
 */
class Profile implements Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"profile_get_collection","profile_post_collection","profile_get_item","profile_put_item"})
     */
    private $about;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"profile_get_collection","profile_post_collection","profile_get_item","profile_put_item"})
     */
    private $stage_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="profile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paypal;

    public function __toString()
    {
        return $this->getPseudo();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): self
    {
        $this->about = $about;

        return $this;
    }

    public function getStageName(): ?string
    {
        return $this->stage_name;
    }

    public function setStageName(?string $stage_name): self
    {
        $this->stage_name = $stage_name;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->image,

        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,

            ) = unserialize($serialized);
    }

    public function getPaypal(): ?string
    {
        return $this->paypal;
    }

    public function setPaypal(?string $paypal): self
    {
        $this->paypal = $paypal;

        return $this;
    }
}