<?php

namespace App\Entity;

use App\Service\ReferenceNumber;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ms_feedback")
 * @ORM\Entity(repositoryClass="App\Repository\FeedbackRepository")
 */
class Feedback extends Base
{
    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $userId;
    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $title;
    /**
     * @ORM\Column(type="text")
     */
    protected $message;
    /**
     * @ORM\Column(type="array")
     */
    protected $data = [];
    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $category = '';
    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $status = 'New';
    /**
     * @ORM\Column(type="array")
     */
    protected $emailSubscriptions = [];
    /**
     * @ORM\Column(type="array")
     */
    protected $screenshots = [];
    /**
     * @ORM\Column(type="boolean")
     */
    protected $deleted = false;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $private = false;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $waiting = false;
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    protected $ref;
    /**
     * @ORM\OneToMany(targetEntity="FeedbackComment", mappedBy="feedback")
     * @ORM\OrderBy({"added"="DESC"})
     */
    protected $comments;
    /**
     * @ORM\Column(type="array")
     */
    protected $history = [];
    /**
     * if true, send an email that this feedback has updated
     */
    private $changed = false;

    public function __construct()
    {
        parent::__construct();

        $this->comments = new ArrayCollection();
        $this->ref = ReferenceNumber::generate();
    }

    public function data()
    {
        return [
            'id'            => $this->id,
            'ref'           => $this->ref,
            'title'         => $this->title,
            'message'       => $this->message,
            'category'      => $this->category,
            'status'        => $this->status,
            'data'          => $this->data,
            'private'       => $this->private,
            'deleted'       => $this->deleted,
            'history'       => $this->history,
        ];
    }

    public function hasChanged()
    {
        return $this->changed;
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->addHistory("Title", $this->title, $title);
        $this->title = $title;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message)
    {
        $this->addHistory("Message", $this->message, $message);
        $this->message = $message;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category)
    {
        $this->addHistory("Category", $this->category, $category);
        $this->changed = ($category != $this->category);
        $this->category = $category;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->addHistory("Status", $this->status, $status);
        $this->changed = ($status != $this->status);
        $this->status = $status;

        return $this;
    }

    public function getEmailSubscriptions($implode = false)
    {
        return $implode ? implode(',', $this->emailSubscriptions) : $this->emailSubscriptions;
    }

    public function setEmailSubscriptions(array $emailSubscriptions)
    {
        $this->emailSubscriptions = $emailSubscriptions;

        return $this;
    }

    public function addEmailSubscription(string $email)
    {
        if (!in_array($email, $this->emailSubscriptions)) {
            $this->emailSubscriptions[] = $email;
        }

        return $this;
    }

    public function getScreenshots(): array
    {
        return $this->screenshots;
    }

    public function setScreenshots(array $screenshots)
    {
        $this->screenshots = $screenshots;

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

    public function isPrivate(): bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private)
    {
        $this->private = $private;

        return $this;
    }

    public function isWaiting(): bool
    {
        return $this->waiting;
    }

    public function setWaiting(bool $waiting)
    {
        $this->waiting = $waiting;

        return $this;
    }

    public function getRef()
    {
        return $this->ref;
    }

    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    public function addComment(FeedbackComment $comment)
    {
        $this->comments[] = $comment;
    }

    public function addHistory(string $type, $old, $new)
    {
        // don't do anything if blank or the same
        if (!$old || !$new || $old === $new) {
            return $this;
        }

        $this->history[] = sprintf('[%s UTC] %s: %s --> %s', date('Y-m-d H:i:s'), $type, $old, $new);
        return $this;
    }

    public function getHistory(): array
    {
        return $this->history;
    }

    public function setHistory(array $history)
    {
        $this->history = $history;

        return $this;
    }
}
