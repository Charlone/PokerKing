<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeckController extends AbstractController
{
    public $suits;
    public $faces;
    public $cards;

    public function __construct() {
        $this->suits = ['H','S','D','C'];
        $this->faces = ['2','3','4','5','6','7','8','9','T','J','Q','K','A'];
        $this->initialiseCards();
    }

    public function initialiseCards()
    {
        foreach ($this->suits AS $suit) {
            foreach ($this->faces AS $face) {
                $deck[] = $face.$suit;
                $this->cards = $deck;
            }
        }
    }

    public function getDeck() :array
    {
        return $this->cards;
    }

    public function setDeck(Array $deck) {
        $this->cards = $deck;
    }

    public function shuffleDeck()
    {
        return shuffle($this->cards);
    }
}