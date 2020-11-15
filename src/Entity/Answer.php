<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ApiResource(
 *  collectionOperations={"GET", "POST"},
 *  itemOperations={"GET", "DELETE", 
 *      "report"={
 *          "method"="PUT",
 *          "path"="/answers/{id}/report",
 *          "controller"="App\Controller\ReportAnswerController",
 *          "openapi_context"={
 *              "summary"="Report an answer"
 *          }
 *      },
 *      "validReport"={
 *          "method"="PUT",
 *          "path"="/answers/{id}/validReport",
 *          "controller"="App\Controller\ValidReportAnswerController",
 *          "openapi_context"={
 *              "summary"="Validate a reported answer"
 *          }
 *      }
 *  },
 *  attributes={
 *      "order"={"sentAt":"ASC"}
 *  },
 *  normalizationContext={
 *      "groups"={"answers_read"}
 *  }
 * )
 */
class Answer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"answers_read", "comments_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"answers_read", "comments_read"})
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"answers_read", "comments_read"})
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"answers_read", "comments_read"})
     */
    private $sentAt;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"answers_read"})
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"answers_read", "comments_read"})
     */
    private $user;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
