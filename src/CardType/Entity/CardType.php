<?php

namespace App\CardType\Entity;

use App\Traits\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\CardType\Repository\CardTypeRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="card_type")
 *
 * @package App\CardType;
 *
 * @UniqueEntity("title")
 */
class CardType
{
    use Timestamps;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=false, unique=true)
     * @var string
     *
     * @Assert\Type("string")
     */
    private $title;

    /**
     * @ORM\Column(name="power", type="integer", nullable=false)
     * @var int
     *
     * @Assert\Type("integer")
     * @Assert\PositiveOrZero
     */
    private $power;

    /**
     * @ORM\Column(name="immortal", type="boolean", nullable=false)
     * @var bool
     *
     * @Assert\Type("boolean")
     */
    private $immortal;

    /**
     * @return int
     *
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return CardType
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int
     */
    public function getPower(): int
    {
        return $this->power;
    }

    /**
     * @param int $power
     * @return CardType
     */
    public function setPower(int $power): self
    {
        $this->power = $power;
        return $this;
    }

    /**
     * @return bool
     */
    public function getImmortal(): bool
    {
        return $this->immortal;
    }

    /**
     * @param bool $immortal
     * @return CardType
     */
    public function setImmortal(bool $immortal): self
    {
        $this->immortal = $immortal;
        return $this;
    }
}
