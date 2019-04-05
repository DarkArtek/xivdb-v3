<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ms_feedback_comment")
 * @ORM\Entity(repositoryClass="App\Repository\FeedbackCommentRepository")
 */
class FeedbackComment extends Base
{
    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $userId;
    /**
     * @ORM\Column(type="text")
     */
    protected $message;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $deleted = false;
    /**
     * @ORM\ManyToOne(targetEntity="Feedback", inversedBy="comments")
     * @ORM\JoinColumn(name="feedback_id", referencedColumnName="id")
     */
    protected $feedback;


    public function data()
    {
        return [
            'id'            => $this->id,
            'message'       => $this->message,
            'deleted'       => $this->deleted,
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function getAdded(): int
    {
        return $this->added;
    }

    public function setAdded(int $added)
    {
        $this->added = $added;

        return $this;
    }

    public function getUpdated(): int
    {
        return $this->updated;
    }

    public function setUpdated(?int $updated = null)
    {
        $this->updated = $updated ?: time();

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function setFeedback(Feedback $feedback)
    {
        $this->feedback = $feedback;

        return $this;
    }
}
