<?php


namespace PokerHands\Model;


class Card
{
    private $suit;
    private $rank;

    public function __construct($rank, $suit) {
        $this->suit = $suit;
        $this->rank = $this->resolveNumericRank($rank);
    }

    public function getRank() {
        return $this->rank;
    }

    public function getSuit() {
        return $this->suit;
    }

    private function resolveNumericRank($rank) {

        switch($rank) {
            case "A":
                return 14;
            case "K":
                return 13;
            case "Q":
                return 12;
            case "J":
                return 11;
            case "T":
                return 10;
            default:
                return (int) $rank;
        }
    }

    public function toString() {
        $rank = $this->rank;

        switch($rank) {
            case 14:
                $rank = "A";
                break;
            case 13:
                $rank = "K";
                break;
            case 12:
                $rank = "Q";
                break;
            case 11:
                $rank = "J";
                break;
            case 10:
                $rank = "T";
                break;
            default:
                $rank = (string) $rank;
                break;
        }

        return $rank . $this->suit;
    }
}