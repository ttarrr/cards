<?php

namespace App\Deck\Entity;

use App\CardType\Entity\CardType;
use App\Traits\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="deck_entry")
 *
 * @package App\Deck;
 */
class DeckEntry
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
     * @ORM\ManyToOne(targetEntity="App\CardType\Entity\CardType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cardType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Deck\Entity\Deck", inversedBy="deckEntries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deck;

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return CardType
     */
    public function getCardType(): CardType
    {
        return $this->cardType;
    }

    /**
     * @param CardType $cardType
     * @return DeckEntry
     */
    public function setCardType(CardType $cardType): self
    {
        $this->cardType = $cardType;
        return $this;
    }

    /**
     * @param Deck|null $deck
     * @return DeckEntry
     */
    public function setDeck(?Deck $deck): self
    {
        $this->deck = $deck;
        return $this;
    }
}
