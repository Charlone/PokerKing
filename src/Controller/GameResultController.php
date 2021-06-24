<?php

namespace App\Controller;
use App\Entity\GameRounds;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GameResultController extends AbstractController
{
    protected $cardsP1;
    protected $cardsP2;

    public function getCardsP1() :array
    {
        return $this->cardsP1;
    }

    public function setCardsP1(string $hand) :self
    {
        $this->cardsP1 = explode(',', $hand);

        return $this;
    }

    public function getCardsP2() :array
    {
        return $this->cardsP2;
    }

    public function setCardsP2(string $hand) :self
    {
        $this->cardsP2 = explode(',', $hand);

        return $this;
    }

    public function getCardValue($card) :int
    {
        switch ($card) {
            case 'T': $value = 10; break;
            case 'J': $value = 11; break;
            case 'Q': $value = 12; break;
            case 'K': $value = 13; break;
            case 'A': $value = 14; break;
            default: $value = intval($card);
        }

        return $value;
    }

    public function handValue(array $hand) :array
    {
        for ($i = 0; $i < count($hand); $i++) {
            $facesValue[$i] = substr($hand[$i], 0, 1);
            $facesValue[$i] = $this->getCardValue($facesValue[$i]);
        }

        sort($facesValue);

        return $facesValue;
    }

    public function straight(array $values) :bool
    {
        for ($i = 1; $i < count($values); $i++) {
            if ($values[$i] !== ($values[$i-1])+1) {
                return false;
            }
        }

        return true;
    }

    public function getCardFaces(array $hand) :array
    {
        $faces = [];

        for ($i = 0; $i < count($hand); $i++) {
            $faces[$i] = substr($hand[$i], 0, 1);
        }

        return $faces;
    }

    public function getCardSuits(array $hand) :array
    {
        $suits = [];

        for ($i = 0; $i < count($hand); $i++) {
            $suits[$i] = substr($hand[$i], 1, 1);
        }

        return $suits;
    }

    public function highCard(array $handFaces, array $handFacesValue) {
        if (count(array_count_values($handFaces)) === 5 && $this->straight($handFacesValue) === false) {
            return max($handFacesValue);
        }
    }

    public function onePair(array $handFaces, array $handFacesValue) {
        if (count(array_count_values($handFaces)) === 4) {
            $highestDuplicate = (max(array_unique(array_diff_assoc($handFacesValue, array_unique($handFacesValue)))));

            return (15 + $highestDuplicate);
        }
    }

    public function twoPair(array $handFaces, array $handFacesValue) {
        if (count(array_count_values($handFaces)) === 3 && max(array_count_values($handFacesValue)) === 2) {
            $highestDuplicate = (max(array_unique(array_diff_assoc($handFacesValue, array_unique($handFacesValue)))));

            return (30 + $highestDuplicate);
        }
    }

    public function threeOfAKind(array $handFaces, array $handFacesValue) {
        if (count(array_count_values($handFaces)) === 3
            && max(array_count_values($handFacesValue)) === 3) {
            $highestDuplicate = (max(array_unique(array_diff_assoc($handFacesValue, array_unique($handFacesValue)))));

            return (45 + $highestDuplicate);
        }
    }

    public function isStraight(array $handFacesValue, array $handSuits, array $handFaces) {
        if ($this->straight($handFacesValue) === true
            && count(array_unique($handSuits)) !== 1
            || preg_grep("/^[A]{1}$|^[2]{1}$|^[3]{1}$|^[4]{1}$|^[5]{1}$/",$handFaces)) {
            
            return (60 + max($handFacesValue));
        }
    }

    public function flush(array $handSuits, array $handFacesValue) {
        if (count(array_unique($handSuits)) === 1 && $this->straight($handFacesValue) !== true) {
            return (75 + max($handFacesValue));
        }
    }

    public function fullHouse(array $handFaces, array $handFacesValue) {
        if (max(array_count_values($handFaces)) === 3 && count(array_unique($handFacesValue)) == 2) {
            $highestDuplicate = max(array_diff_assoc(array_diff_assoc($handFacesValue, array_unique($handFacesValue)), array_unique(array_diff_assoc($handFacesValue, array_unique($handFacesValue)))));
            
            return (90 + $highestDuplicate);
        }
    }

    public function fourOfAKind(array $handFacesValue) {
        if (max(array_count_values($handFacesValue)) === 4) {
            $highestDuplicate = max(array_diff_assoc(array_diff_assoc($handFacesValue, array_unique($handFacesValue)), array_unique(array_diff_assoc($handFacesValue, array_unique($handFacesValue)))));
            
            return (105 + $highestDuplicate);
        }
    }

    public function straightFlush(array $handSuits,array $handFaces, array $handFacesValue) {
        if (count(array_unique($handSuits)) === 1 && $this->straight($handFacesValue) === true
            || count(array_unique($handSuits)) === 1 && preg_grep("/^[A]{1}$|^[2]{1}$|^[3]{1}$|^[4]{1}$|^[5]{1}$/",$handFaces)) {
            return (120 + max($handFacesValue));
        }
    }

    public function royalFlush(array $handSuits, array $handFaces, array $handFacesValue) {
        if (count(array_unique($handSuits)) === 1 && $this->straight($handFacesValue) === true && preg_grep("/^[T]{1}$|^[J]{1}$|^[Q]{1}$|^[K]{1}$|^[A]{1}$/",$handFaces)) {
            return 135;
        }
    }

    public function getResult(array $hand) :array
    {
        $faces = $this->getCardFaces($hand);
        $suits = $this->getCardSuits($hand);
        $facesValue = $this->handValue($hand);

        if ($this->royalFlush($suits, $faces, $facesValue)) {
            $value = $this->royalFlush($suits, $faces, $facesValue);
            $rank = "Royal Flush";
        } elseif ($this->straightFlush($suits, $faces, $facesValue)) {
            $value = $this->straightFlush($suits, $faces, $facesValue);
            $rank = "Straight Flush";
        } elseif ($this->fourOfAKind($facesValue)) {
            $value = $this->fourOfAKind($facesValue);
            $rank = "4 of a Kind";
        } elseif ($this->fullHouse($faces,$facesValue)) {
            $value = $this->fullHouse($faces, $facesValue);
            $rank = "Full House";
        } elseif ($this->flush($suits, $facesValue)) {
            $value = $this->flush($suits, $facesValue);
            $rank = "Flush";
        } elseif ($this->isStraight($facesValue, $suits, $faces) === true) {
            $value = $this->isStraight($facesValue, $suits, $faces);
            $rank = "Straight";
        } elseif ($this->threeOfAKind($faces, $facesValue)) {
            $value = $this->threeOfAKind($faces, $facesValue);
            $rank = "3 of a Kind";
        } elseif ($this->twoPair($faces, $facesValue)) {
            $value = $this->twoPair($faces, $facesValue);
            $rank = "Two Pair";
        } elseif ($this->onePair($faces, $facesValue)) {
            $value = $this->onePair($faces, $facesValue);
            $rank = "One Pair";
        } else {
            $value = $this->highCard($faces, $facesValue);
            $rank = "High Card";
        }

        return array("PlayerHand" => $hand, "Value" => $value, "Rank" => $rank);
    }

    function persistGameRound($playerId, $betAmount, $value, $outcome, $reward = null) {
        $cards = implode(' ', $this->getCardsP1());

        $game = new GameRounds();
        $game->setPlayerId($playerId);
        $game->setHand($cards);
        $game->setBetAmount($betAmount);
        if ($outcome === 'win') {
            $game->setOutcome($reward);
        } elseif ($outcome === 'loss') {
            $game->setOutcome(-($betAmount));
        } else {
            $game->setOutcome(0);
        }
        $game->setHandScore($value);
        $game->setCreatedAt(new \DateTime("now"));

        $em = $this->getDoctrine()
            ->getManager();
        $em->persist($game);
        $em->flush();

        $em->getConnection()->close();
    }

    /**
     * @Route("/game/play/result", name="game_play_result", methods="GET", format="JSON", options={"expose"=true})
     */
    public function outcome() :JsonResponse
    {
        $request = Request::createFromGlobals();

        if($request->query->get('player_id')) {
            $playerId = $request->query->get('player_id');
        } else {
            $playerId = $this->getUser()->getId();
        }

        $betAmount = $request->get('bet_amount');
        $reward = ($betAmount * 1.5);

        $this->setCardsP1($request->get('player_hand'));
        $this->setCardsP2($request->get('opponent_hand'));

        $playerResult = $this->getResult($this->getCardsP1());
        $opponentResult = $this->getResult($this->getCardsP2());

        if ($playerResult['Value'] > $opponentResult['Value']) {
            $this->persistGameRound($playerId, $betAmount, $playerResult['Value'], 'win', ($reward - $betAmount));

            return new JsonResponse([
                'player_id' => $playerId,
                'winner' => $this->getUser()->getUsername(),
                'hand' => $this->getCardsP1(),
                'rank' => $playerResult['Rank'],
                'bet_amount' => $betAmount,
                'win_amount' => $reward
            ]);
        } elseif ($playerResult['Value'] < $opponentResult['Value']) {
            $this->persistGameRound($playerId, $betAmount, $playerResult['Value'], 'loss',-($betAmount));

            return new JsonResponse([
                'winner' => 'Opponent',
                'hand' => $this->getCardsP2(),
                'rank' => $playerResult['Rank'],
                'bet_amount' => $betAmount,
                'loss_amount' => -($betAmount)
            ]);
        } else {
            $this->persistGameRound($playerId, $betAmount, $playerResult['Value'], 'draw', 0);

            return new JsonResponse([
                'winner' => 'Game ended in a draw',
                'playerHand' => $this->getCardsP1(),
                'opponentHand' => $this->getCardsP2()
            ]);
        }
    }
}