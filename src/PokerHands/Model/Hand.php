<?php
namespace PokerHands\Model;

use PokerHands\Enum\HandType;

class Hand
{
    private $cards = [];
    private $type;
    private $highest;

    public function __construct($cards) {

        usort($cards, function($a, $b) {
            return $a->getRank() < $b->getRank();
        });

        $this->cards = $cards;
        $this->highest = $this->cards[0]->getRank();

        $this->resolveHandType();
    }

    public function getHandType() {
        return $this->type;
    }

    public function getHighest() {
        return $this->highest;
    }

    public function getCard($idx) {
        return $this->cards[$idx];
    }

    private function resolveHandType() {

        $uniqueCardValueCount = function($offset, $length) {
            return count(array_unique(array_map(function($card) { return $card->getRank(); }, array_slice($this->cards,$offset, $length))));
        };

        if (count(array_unique(array_map(function($card) { return $card->getRank(); }, $this->cards))) == 5 && $this->cards[0]->getRank() - $this->cards[4]->getRank() == 4) {
            if (count(array_unique(array_map(function($card) { return $card->getSuit() ;}, $this->cards))) == 1) {
                if ($this->highest == 14) {
                    $this->type = HandType::ROYAL_FLUSH;
                    return;
                }

                $this->type = HandType::STRAIGHT_FLUSH;
                return;
            }

            $this->type = HandType::STRAIGHT;
            return;
        }

        if ($uniqueCardValueCount(0,4)== 1 || $uniqueCardValueCount(1,4) == 1) {
            $this->type = HandType::FOUR_OF;
            return;
        }

        if (
            ($uniqueCardValueCount(0,3) == 1 && $uniqueCardValueCount(3,2) == 1) ||
            ($uniqueCardValueCount(0,2) == 1 && $uniqueCardValueCount(2,3) == 1)
        ) {
            $this->type = HandType::FULL_HOUSE;
            return;
        }

        if ($uniqueCardValueCount(0,3) == 1 ||
            $uniqueCardValueCount(1,3) == 1 ||
            $uniqueCardValueCount(2,3) == 1) {
            $this->type = HandType::THREE_OF;
            return;
        }

        if (($uniqueCardValueCount(0,2) == 1 && $uniqueCardValueCount(2,2) == 1) ||
            ($uniqueCardValueCount(0,2) == 1 && $uniqueCardValueCount(3,2) == 1) ||
            ($uniqueCardValueCount(1,2) == 1 && $uniqueCardValueCount(3,2) == 1)
        ) {
            $this->type = HandType::TWO_PAIRS;
            return;
        }

        if ($uniqueCardValueCount(0,2) == 1 ||
            $uniqueCardValueCount(1,2) == 1 ||
            $uniqueCardValueCount(2,2) == 1 ||
            $uniqueCardValueCount(3,2) == 1
        ) {
            $this->type = HandType::ONE_PAIR;
            return;
        }

        $this->type = HandType::HIGH_CARD;
    }

    public function toJson() {
        $cards = [];

        foreach ($this->cards as $card) {
            $cards[] = $card->toString();
        }

        return json_encode([
            'cards' => implode(',', $cards),
            'handtype' => HandType::toString($this->getHandType())
        ]);

    }
}