<?php declare(strict_types=1);

namespace App\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait Timestamps
 * @package App\Traits;
 */
trait Timestamps
{
    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     * @var DateTimeInterface
     */
    private $updatedAt;

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt() : DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt() : DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt() : void
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAt() : void
    {
        $this->updatedAt = new \DateTime('now');
    }
}
