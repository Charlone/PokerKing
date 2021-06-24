<?php

namespace App\Entity;

use App\Repository\GameBalanceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameBalanceRepository::class)
 */
class GameBalance
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
     * @ORM\Column(type="integer")
     */
    private $start_balance;

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

    public function getStartBalance(): ?int
    {
        return $this->start_balance;
    }

    public function setStartBalance(int $start_balance): self
    {
        $this->start_balance = $start_balance;

        return $this;
    }
}
