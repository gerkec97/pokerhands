<?php
namespace PokerHands\Repository;

use Psr\Container\ContainerInterface;

class GameRound
{
    private $handOne;
    private $handTwo;
    private $winningHand;
    private $container;

    /**
     * GameRound constructor.
     * @param ContainerInterface $container
     * @param string $handOne
     * @param string $handTwo
     * @param int $winningHand
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function appendGameRound($handOne, $handTwo, $winningHand) {
        $this->handOne = $handOne;
        $this->handTwo = $handTwo;
        $this->winningHand = $winningHand;
        $this->save();
    }

    private function save() {
        $pdo = $this->container->get('PDOConnection');
        $stmt = $pdo->prepare("INSERT INTO game_rounds (hand_one, hand_two, winning_hand) VALUES(:handOne,:handTwo,:winningHand)");
        $stmt->execute([
            ':handOne' => $this->handOne,
            ':handTwo' => $this->handTwo,
            ':winningHand' => $this->winningHand
        ]);
    }

    public function countWinningHandsByPlayer($player) {
        $pdo = $this->container->get('PDOConnection');
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM game_rounds WHERE winning_hand=:player");
        $stmt->execute([':player' => $player]);
        $won = $stmt->fetch();
        return $won[0];
    }
}