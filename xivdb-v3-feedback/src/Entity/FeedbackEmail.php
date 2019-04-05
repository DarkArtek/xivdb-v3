<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ms_feedback_email")
 * @ORM\Entity(repositoryClass="App\Repository\FeedbackEmailRepository")
 */
class FeedbackEmail extends Base
{
    const SINGLE = 1;
    const MULTI = 2;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $sent = false;
    /**
     * @ORM\Column(type="integer")
     */
    protected $type;
    /**
     * @ORM\Column(type="text")
     */
    protected $email;
    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $subject;
    /**
     * @ORM\Column(type="string", length=40)
     */
    protected $template;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $feedbackId = null;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $commentId = null;

    public function __construct(
        string $type,
        string $email,
        string $subject,
        string $template,
        ?string $feedbackId = null,
        ?string $commentId = null
    ) {
        parent::__construct();
        
        $this->type = $type;
        $this->email = $email;
        $this->subject = $subject;
        $this->template = $template;
        $this->feedbackId = $feedbackId;
        $this->commentId = $commentId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAdded(): int
    {
        return $this->added;
    }

    public function isSent(): bool
    {
        return $this->sent;
    }

    public function setSent(bool $sent)
    {
        $this->sent = $sent;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getFeedbackId(): ?string
    {
        return $this->feedbackId;
    }

    public function getCommentId(): ?string
    {
        return $this->commentId;
    }
}
