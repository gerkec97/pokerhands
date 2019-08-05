<?php

namespace PokerHands\Validator;

class Card
{
    const SUITS = ['C','S','H','D'];
    const RANKS = ['A','K','Q','J','T','9','8','7','6','5','4','3','2'];

    /**
     * We have two players so we do have to have 10 cards in total
     *
     * @param $cards
     * @return bool
     */
    public static function validateTotalCount($cards) {
        return count($cards) == 10;
    }

    /**
     * Check that the passes card has a valid suit and rank
     *
     * @param $card
     * @return bool
     */
    public static function validate($card) {
        return strlen($card) === 2 && in_array($card[0],self::RANKS) && in_array($card[1], self::SUITS);
    }
}