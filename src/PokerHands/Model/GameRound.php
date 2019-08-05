<?php
namespace PokerHands\Model;

use PokerHands\Enum\HandType;

class GameRound
{
    private $handOne;
    private $handTwo;
    private $winningHand = 0;

    public function getHandOne() {
        return $this->handOne;
    }

    public function getHandTwo() {
        return $this->handTwo;
    }

    /**
     * @return int
     */
    public function getWinningHand() {
        return $this->winningHand;
    }

    public function __construct($hands)
    {
        $this->handOne = $hands[0];
        $this->handTwo = $hands[1];
    }

    public function calculateWinningHand() {
        if ($this->handOne->getHandType() > $this->handTwo->getHandType()) {
            $this->winningHand = 1;
        } else if ($this->handOne->getHandType() < $this->handTwo->getHandType()) {
            $this->winningHand = 2;
        } else {
            switch($this->handOne->getHandType()) {
                case HandType::ONE_PAIR:
                    $this->winningHand = $this->findHighestFromPair();
                    break;
                case HandType::TWO_PAIRS:
                    $this->winningHand = $this->findHighestFromTwoPairs();
                    break;
                case HandType::THREE_OF:
                    $this->winningHand = $this->findHighestFromThree();
                    break;
                case HandType::FOUR_OF:
                    $this->winningHand = $this->findHighestFromFour();
                    break;
                case HandType::FULL_HOUSE:
                    $this->winningHand = $this->findHighestFromFullHouse();
                    break;
                case HandType::STRAIGHT_FLUSH:
                case HandType::STRAIGHT:
                    $this->winningHand = $this->findHighest(true);
                    break;
                case HandType::FLUSH:
                    $this->winningHand = $this->findHighestFromFlush();
                    break;
                case HandType::HIGH_CARD:
                    $this->winningHand = $this->findHighest();
                    break;
                default:
                    $this->winningHand = 0;
            }
        }
    }

    private function findHighestFromPair() {
        $handOneFound = false;
        $handTwoFound = false;

        for ($i = 0; $i < 4; $i++) {
            if (!$handOneFound && $this->handOne->getCard($i)->getRank() == $this->handOne->getCard($i+1)->getRank()) {
                $handOnePairValue = $this->handOne->getCard($i)->getRank();
                $handOnePairIndex = $i;
                $handOneFound = true;
            }

            if (!$handTwoFound && $this->handTwo->getCard($i)->getRank() == $this->handTwo->getCard($i+1)->getRank()) {
                $handTwoPairValue = $this->handTwo->getCard($i)->getRank();
                $handTwoPairIndex = $i;
                $handTwoFound = true;
            }
        }

        if ($handOnePairValue > $handTwoPairValue) {
            return 1;
        } else if ($handOnePairValue < $handTwoPairValue) {
            return 2;
        }

        return $this->checkKickers([
            [$handOnePairIndex, $handOnePairIndex++],
            [$handTwoPairIndex, $handTwoPairIndex++]
        ]);
    }

    private function checkKickers($skip) {
        $handOneKickers = [];
        $handTwoKickers = [];

        for ($i = 0; $i < 4; $i++) {
            if (!in_array($i, $skip[0])) {
                $handOneKickers[] = $this->handOne->getCard($i)->getRank();
            }

            if (!in_array($i, $skip[1])) {
                $handTwoKickers[] = $this->handTwo->getCard($i)->getRank();
            }
        }

        rsort($handOneKickers);
        rsort($handTwoKickers);

        for ($i = 0; $i < 2; $i++) {
            if ($handOneKickers[$i] > $handTwoKickers[$i]) {
                return 1;
            } else if ($handOneKickers[$i] < $handTwoKickers[$i]) {
                return 2;
            }
        }

        return 0;
    }

    private function findHighestFromTwoPairs() {

        $handOneGrouped = [];
        $handTwoGrouped = [];

        for ($i = 0; $i < 4; $i++) {
            $card = $this->handOne->getCard($i)->getRank();

            if (array_key_exists($card, $handOneGrouped)) {
                $handOneGrouped[$card]++;
            } else {
                $handOneGrouped[$card] = 1;
            }

            $card = $this->handTwo->getCard($i)->getRank();

            if (array_key_exists($card, $handTwoGrouped)) {
                $handTwoGrouped[$card]++;
            } else {
                $handTwoGrouped[$card] = 1;
            }
        }

        $handOnePairs = [];
        $handOneKicker = 0;
        $handTwoPairs = [];
        $handTwoKicker = 0;

        foreach ($handOneGrouped as $rank => $count) {
            if ($count == 2) {
                $handOnePairs[] = $rank;
            } else {
                $handOneKicker = $rank;
            }
        }

        foreach ($handTwoGrouped as $rank => $count) {
            if ($count == 2) {
                $handTwoPairs[] = $rank;
            } else {
                $handTwoKicker = $rank;
            }
        }

        rsort($handOnePairs);
        rsort($handTwoPairs);

        for ($i = 0; $i < 2; $i++) {
            if($handOnePairs[$i] > $handTwoPairs[$i]) {
                return 1;
            } else if ($handOnePairs[$i] > $handTwoPairs[$i]) {
                return 2;
            }
        }

        if ($handOneKicker > $handTwoKicker) {
            return 1;
        } else if ($handOneKicker < $handTwoKicker) {
            return 2;
        }

        return 0;
    }

    private function findHighestFromThree() {

        $handOneGrouped = [];
        $handTwoGrouped = [];

        for ($i = 0; $i < 4; $i++) {
            $card = $this->handOne->getCard($i)->getRank();

            if (array_key_exists($card, $handOneGrouped)) {
                $handOneGrouped[$card]++;
            } else {
                $handOneGrouped[$card] = 1;
            }

            $card = $this->handTwo->getCard($i)->getRank();

            if (array_key_exists($card, $handTwoGrouped)) {
                $handTwoGrouped[$card]++;
            } else {
                $handTwoGrouped[$card] = 1;
            }
        }

        $handOneThree = 0;
        $handOneKickers = [];
        $handTwoThree = 0;
        $handTwoKickers = [];

        foreach ($handOneGrouped as $rank => $count) {
            if ($count == 3) {
                $handOneThree = $rank;
            } else {
                $handOneKickers[] = $rank;
            }
        }

        foreach ($handTwoGrouped as $rank => $count) {
            if ($count == 3) {
                $handTwoThree = $rank;
            } else {
                $handTwoKickers[] = $rank;
            }
        }

        if ($handOneThree > $handOneThree) {
            return 1;
        } else if ($handOneThree < $handTwoThree) {
            return 2;
        }

        rsort($handOneKickers);
        rsort($handTwoKickers);

        for ($i = 0; $i < 2; $i++) {
            if($handOneKickers[$i] > $handTwoKickers[$i]) {
                return 1;
            } else if ($handOneKickers[$i] > $handTwoKickers[$i]) {
                return 2;
            }
        }

        return 0;
    }

    private function findHighestFromFour() {
        $handOneGrouped = [];
        $handTwoGrouped = [];

        for ($i = 0; $i < 4; $i++) {
            $card = $this->handOne->getCard($i)->getRank();

            if (array_key_exists($card, $handOneGrouped)) {
                $handOneGrouped[$card]++;
            } else {
                $handOneGrouped[$card] = 1;
            }

            $card = $this->handTwo->getCard($i)->getRank();

            if (array_key_exists($card, $handTwoGrouped)) {
                $handTwoGrouped[$card]++;
            } else {
                $handTwoGrouped[$card] = 1;
            }
        }

        $handOneFour = 0;
        $handOneKicker = 0;
        $handTwoFour = 0;
        $handTwoKicker = 0;

        foreach ($handOneGrouped as $rank => $count) {
            if ($count == 4) {
                $handOneFour = $rank;
            } else {
                $handOneKicker = $rank;
            }
        }

        foreach ($handTwoGrouped as $rank => $count) {
            if ($count == 4) {
                $handTwoFour = $rank;
            } else {
                $handTwoKicker = $rank;
            }
        }

        if ($handOneFour > $handTwoFour) {
            return 1;
        } else if ($handOneFour < $handTwoFour) {
            return 2;
        }

        if($handOneKicker > $handTwoKicker) {
            return 1;
        } else if ($handOneKicker > $handTwoKicker) {
            return 2;
        }

        return 0;
    }

    private function findHighestFromFullHouse() {

        $handOneGrouped = [];
        $handTwoGrouped = [];

        for ($i = 0; $i < 4; $i++) {
            $card = $this->handOne->getCard($i)->getRank();

            if (array_key_exists($card, $handOneGrouped)) {
                $handOneGrouped[$card]++;
            } else {
                $handOneGrouped[$card] = 1;
            }

            $card = $this->handTwo->getCard($i)->getRank();

            if (array_key_exists($card, $handTwoGrouped)) {
                $handTwoGrouped[$card]++;
            } else {
                $handTwoGrouped[$card] = 1;
            }
        }

        $handOneTreble = 0;
        $handOneDouble = 0;
        $handTwoTreble = 0;
        $handTwoDouble = 0;

        foreach ($handOneGrouped as $rank => $count) {
            if ($count == 3) {
                $handOneTreble = $rank;
            } else if ($count == 2) {
                $handOneDouble = $rank;
            }
        }

        foreach ($handTwoGrouped as $rank => $count) {
            if ($count == 3) {
                $handTwoTreble = $rank;
            } else if ($count == 2) {
                $handTwoDouble = $rank;
            }
        }

        if ($handOneTreble > $handTwoTreble) {
            return 1;
        } else if ($handOneTreble < $handTwoTreble) {
            return 2;
        } else if ($handOneDouble > $handTwoDouble) {
            return 1;
        } else if ($handOneDouble < $handTwoDouble) {
            return 2;
        }

        return 0;
    }

    private function findHighestFromFlush() {
        if($this->handOne->getHighest() > $this->handTwo->getHighest()) {
            return 1;
        } else if ($this->handOne->getHighest() < $this->handTwo->getHighest()) {
            return 2;
        }
    }

    private function findHighest($isStraight = false) {
        if ($isStraight) {
            if ($this->handOne->getHighest() > $this->handTwo->getHighest()) {
                return 1;
            } else if($this->handOne->getHighest() < $this->handTwo->getHighest()) {
                return 2;
            }
            return 0;
        }

        for($i = 0; $i < 5; $i++) {
            if ($this->handOne->getCard($i)->getRank() > $this->handTwo->getCard($i)->getRank()) {
                return 1;
            } else if ($this->handOne->getCard($i)->getRank() < $this->handTwo->getCard($i)->getRank()) {
                return 2;
            }
        }

        return 0;
    }
}