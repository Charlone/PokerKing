<?php

namespace App\Entity;

use App\Repository\GameRoundsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRoundsRepository::class)
 */
class GameRounds
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="id")
     * @ORM\Column(type="integer")
     */
    private $player_id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $hand;

    /**
     * @ORM\Column(type="integer")
     */
    private $hand_score;

    /**
     * @ORM\Column(type="integer")
     */
    private $bet_amount;

    /**
     * @ORM\Column(type="integer")
     */
    private $outcome;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerId(): ?int
    {
        return $this->player_id;
    }

    public function setPlayerId(int $player_id): self
    {
        $this->player_id = $player_id;

        return $this;
    }

    public function getHand(): ?string
    {
        return $this->hand;
    }

    public function setHand(string $hand): self
    {
        $this->hand = $hand;

        return $this;
    }

    public function getHandScore(): ?int
    {
        return $this->hand_score;
    }

    public function setHandScore(int $score): self
    {
        $this->hand_score = $score;

        return $this;
    }

    public function getBetAmount(): ?int
    {
        return $this->bet_amount;
    }

    public function setBetAmount(int $bet_amount): self
    {
        $this->bet_amount = $bet_amount;

        return $this;
    }

    public function getOutcome(): ?int
    {
        return $this->outcome;
    }

    public function setOutcome(int $outcome): self
    {
        $this->outcome = $outcome;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
