<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
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
 */
class Profile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"profile_post_collection","profile_get_item","profile_put_item"})
     */
    private $biography;

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
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"profile_get_collection","profile_post_collection","profile_get_item","profile_put_item"})
     */
    private $price;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\user", cascade={"persist", "remove"})
     * @Groups({"profile_get_collection","profile_post_collection","profile_get_item"})
     */
    private $id_user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): self
    {
        $this->biography = $biography;

        return $this;
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

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIdUser(): ?user
    {
        return $this->id_user;
    }

    public function setIdUser(?user $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }
}
