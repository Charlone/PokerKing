<?php

namespace App\Controller;

use App\Entity\GameBalance;
use App\Entity\GameRounds;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    public function getPlayerId(): string
    {
        $request = Request::createFromGlobals();

        if($request->query->get('player_id')) {
            $player_id = $request->query->get('player_id');
        } else {
            $player_id = $this->getUser()->getId();
        }

        return $player_id;
    }

    public function initialiseBalance($player_id) {
        $check_balance = $this->getDoctrine()
            ->getRepository(GameBalance::class)
            ->findOneBy(
                ['player_id' => $player_id]
            );

        if (!$check_balance) {
            $balance = new GameBalance();
            $balance->setPlayerId($player_id);
            $balance->setStartBalance(10000);

            $em = $this->getDoctrine()
                ->getManager();
            $em->persist($balance);
            $em->flush();

            $em->getConnection()->close();
        }
    }

    public function getGameStats(): array
    {
        $player_id = $this->getPlayerId();

        $player_start_balance = $this->getDoctrine()
            ->getRepository(GameBalance::class)
            ->findOneBy(
                ['player_id' => $player_id]
            );

        $player_game_rounds = $this->getDoctrine()
            ->getRepository(GameRounds::class)
            ->findBy(
                ['player_id' => $player_id]
            );

        $hands = 0;
        $bet_amount = 0;
        $outcome = 0;
        $current_balance = $player_start_balance->getStartBalance();

        foreach ($player_game_rounds as $round) {
            $hands++;
            $bet_amount += $round->getBetAmount();
            $outcome += $round->getOutcome();
            $current_balance += $round->getOutcome();
        }

        return [
            'start_balance' => $player_start_balance->getStartBalance(),
            'current_balance' => $current_balance,
            'hands_played' => $hands,
            'total_bet_amount' => $bet_amount,
            'outcome' => $outcome
        ];
    }

    /**
     * @Route("/game", name="game")
     */
    public function index(): Response
    {
        $player_id = $this->getUser()->getId();

        $this->initialiseBalance($player_id);

        return $this->render('game/home.html.twig', [
            "player_id" => $player_id
        ]);
    }

    /**
     * @Route("/game/index", name="game_index")
     */
    public function gameIndex(): Response
    {
        // variables needed for if game is played fullscreen instead of from iframe in /game
        $player_id = $this->getUser()->getId();

        $this->initialiseBalance($player_id);

        return $this->render('game/index.html.twig', [
            "player_id" => $player_id
        ]);
    }

    /**
     * @Route("/game/stats", name="game_stats", methods="GET", format="JSON", options={"expose"=true})
     */
    public function gameStats(): JsonResponse
    {
        $stats = $this->getGameStats();

        return new JsonResponse(
            $stats
        );
    }

    /**
     * @Route("/game/profile", name="game_profile", methods="GET", format="JSON", options={"expose"=true})
     */
    public function gameProfile(): JsonResponse
    {
        $player_id = $this->getPlayerId();

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(
                ['id' => $player_id]
            );

        return new JsonResponse(
            array(
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'email' => $user->getEmail(),
                'street' => $user->getStreetAddress(),
                'city' => $user->getCity(),
                'country' => $user->getCountry()
            )
        );
    }

    /**
     * @Route("/game/reset", name="game_reset")
     */
    public function gameReset(): JsonResponse
    {
        $player_id = $this->getPlayerId();

        $game_rounds = $this->getDoctrine()
            ->getRepository(GameRounds::class)
            ->findBy(
                ['player_id' => $player_id]
            );

        $em = $this->getDoctrine()
            ->getManager();

        try {
            foreach ($game_rounds AS $game_round) {
                $em->remove($game_round);
                $em->flush();
            }

            return new JsonResponse(
                array(
                    'delete' => 'success'
                )
            );
        } catch (\Exception $exception) {
            return new JsonResponse(
                array(
                    'error' => $exception
                )
            );
        }
    }

    /**
     * @Route("/game/play", name="game_play", methods="GET", format="JSON", options={"expose"=true})
     */
    public function gamePlay(): JsonResponse
    {

    }
}
