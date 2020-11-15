<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ApiResource(
 *  collectionOperations={"GET", "POST"},
 *  itemOperations={"GET", "DELETE", 
 *      "report"={
 *          "method"="PUT",
 *          "path"="/comments/{id}/report",
 *          "controller"="App\Controller\ReportCommentController",
 *          "openapi_context"={
 *              "summary"="Report a comment"
 *          }
 *      },
 *      "validReport"={
 *          "method"="PUT",
 *          "path"="/comments/{id}/validReport",
 *          "controller"="App\Controller\ValidReportCommentController",
 *          "openapi_context"={
 *              "summary"="Validate a reported comment"
 *          }
 *      }
 *  },
 *  attributes={
 *      "order"={"sentAt":"ASC"}
 *  },
 *  normalizationContext={
 *      "groups"={"comments_read"}
 *  }
 * )
 * @ApiFilter(SearchFilter::class, properties={"status"})
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"comments_read", "answers_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"comments_read", "answers_read"})
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"comments_read", "answers_read"})
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"comments_read", "answers_read"})
     */
    private $sentAt;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="comment", orphanRemoval=true)
     * @Groups({"comments_read"})
     * @ApiSubresource
     */
    private $answers;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"comments_read", "answers_read"})
     */
    private $user;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    
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

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setComment($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getComment() === $this) {
                $answer->setComment(null);
            }
        }

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
