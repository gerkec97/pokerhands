<?php

namespace PokerHands\Enum;

/**
 * Class HandType
 * Simple enumerator class describing the possible outcome of a poker hand
 *
 * @package PokerHands
 */
class HandType
{
    const HIGH_CARD = 1;
    const ONE_PAIR = 2;
    const TWO_PAIRS = 3;
    const THREE_OF = 4;
    const STRAIGHT = 5;
    const FLUSH = 6;
    const FULL_HOUSE = 7;
    const FOUR_OF = 8;
    const STRAIGHT_FLUSH = 9;
    const ROYAL_FLUSH = 10;
    
    public static function toString($type) {
        switch($type) {
            case self::HIGH_CARD:
                return 'HIGH CARD';
            case self::ONE_PAIR:
                return 'ONE PAIR';
            case self::TWO_PAIRS:
                return 'TWO PAIRS';
            case self::THREE_OF:
                return 'THREE OF A KIND';
            case self::STRAIGHT:
                return 'STRAIGHT';
            case self::FLUSH:
                return 'FLUSH';
            case self::FULL_HOUSE:
                return 'FULL HOUSE';
            case self::FOUR_OF:
                return 'FOUR OF A KIND';
            case self::STRAIGHT_FLUSH:
                return 'STRAIGHT FLUSH';
            case self::ROYAL_FLUSH:
                return 'ROYAL FLUSH';
        }
    }
}