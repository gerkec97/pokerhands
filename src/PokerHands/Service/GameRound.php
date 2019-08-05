<?php
namespace PokerHands\Service;

use PokerHands\Model\Card;
use PokerHands\Model\Hand;
use PokerHands\Validator\Card as CardValidator;
use PokerHands\Model\GameRound as GameRoundModel;
use PokerHands\Repository\GameRound as GameRoundRepository;
use Psr\Container\ContainerInterface;

class GameRound
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function importGameRound($cards)
    {

        if (!CardValidator::validateTotalCount($cards)) {
            return false;
        }

        foreach ($cards as $rawCard) {
            if (!CardValidator::validate($rawCard)) {
                return false;
            }
        }

        $realCards = [];

        foreach ($cards as $rawCard) {
            $realCards[] = new Card($rawCard[0], $rawCard[1]);
        }

        $hands = [];

        foreach (array_chunk($realCards, 5) as $cards) {
            $hands[] = new Hand($cards);
        }

        $model = new GameRoundModel($hands);
        $model->calculateWinningHand();

        $repository = new GameRoundRepository($this->container);
        $repository->appendGameRound($model->getHandOne()->toJson(), $model->getHandTwo()->toJson(), $model->getWinningHand());
    }

    public function getStatistics() {

        $repository = new GameRoundRepository($this->container);
        $stats = [];

        foreach([1,2] as $playerId) {
            $stats[$playerId] = $repository->countWinningHandsByPlayer($playerId);
        }

        return $stats;
    }
}