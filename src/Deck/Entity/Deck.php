<?php

namespace App\Deck\Entity;

use App\Traits\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Validator\Constraints as Assert;
use App\Http\Validations\CardTypeLimit as CardTypeLimit;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Deck\Repository\DeckRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="deck")
 *
 * @package App\Deck;
 */
class Deck
{
    use Timestamps;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     */
    private $id;

    /**
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @var int
     *
     * @Assert\Type("integer")
     */
    private $userId;

    /**
     * @ORM\OneToMany(targetEntity="App\Deck\Entity\DeckEntry", mappedBy="deck", orphanRemoval=true)
     * @Assert\Count(
     *      max = 10,
     *      maxMessage = "You cannot hold more than {{ limit }} cards in a deck"
     * )
     * @CardTypeLimit\CardTypeLimit
     */
    private $deckEntries;

    /**
     * Deck constructor.
     */
    public function __construct()
    {
        $this->deckEntries = new ArrayCollection();
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNumberOfCards() : int
    {
        return count($this->deckEntries);
    }

    /**
     * @return int
     */
    public function getDeckPower() : int
    {
        $deckPower = 0;

        /** @var DeckEntry $entry */
        foreach ($this->deckEntries as $entry) {
            $deckPower += $entry->getCardType()->getPower();
        }

        return $deckPower;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return Deck
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return Collection|DeckEntry[]
     */
    public function getDeckEntries(): Collection
    {
        return $this->deckEntries;
    }

    /**
     * @param DeckEntry $deckEntry
     * @return Deck
     */
    public function addDeckEntry(DeckEntry $deckEntry): self
    {
        if (!$this->deckEntries->contains($deckEntry)) {
            $this->deckEntries[] = $deckEntry;
            $deckEntry->setDeck($this);
        }

        return $this;
    }

    /**
     * @param DeckEntry $deckEntry
     * @return Deck
     */
    public function removeDeckEntry(DeckEntry $deckEntry): self
    {
        if ($this->deckEntries->removeElement($deckEntry)) {
            $deckEntry->setDeck(null);
        }

        return $this;
    }
}
