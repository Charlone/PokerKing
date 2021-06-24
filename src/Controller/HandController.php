<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HandController extends AbstractController
{
    /**
     * @Route("/game/play/drawHand", name="game_play_draw_hand", methods="GET", format="JSON", options={"expose"=true})
     */
    public function drawHand() :JsonResponse
    {
        $deck = new DeckController();
        $deck->getDeck();
        $deck->shuffleDeck();

        $hand = [];
        $cardNumber = 1;

        for ($i = 1; $i <= 10; $i++) {
            if ($i <= 5) {
                $hand += ["player-card-$i" => $deck->cards[$i]];
                array_shift($deck->cards);
            } else {
                $hand += ["opponent-card-$cardNumber" => $deck->cards[$i]];
                array_shift($deck->cards);
                $cardNumber++;
            }
        }

        return new JsonResponse(
            $hand
        );
    }
}