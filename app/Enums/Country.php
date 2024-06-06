<?php
namespace App\Enums;

use InvalidArgumentException;

class Country
{
    const USA = 'USA';
    const CANADA = 'Canada';
    const UK = 'UK';
    const AUSTRALIA = 'Australia';
    const GERMANY = 'Germany';
    const FRANCE = 'France';
    const ITALY = 'Italy';
    const JAPAN = 'Japan';
    const CHINA = 'China';
    const INDIA = 'India';
    const BRAZIL = 'Brazil';
    const SOUTH_AFRICA = 'South Africa';
    const MEXICO = 'Mexico';
    const RUSSIA = 'Russia';
    const SPAIN = 'Spain';
    const NETHERLANDS = 'Netherlands';
    const SWITZERLAND = 'Switzerland';
    const SWEDEN = 'Sweden';
    const NORWAY = 'Norway';
    const FINLAND = 'Finland';
    const ARGENTINA = 'Argentina';
    const BELGIUM = 'Belgium';
    const CHILE = 'Chile';
    const COLOMBIA = 'Colombia';
    const DENMARK = 'Denmark';
    const EGYPT = 'Egypt';
    const GREECE = 'Greece';
    const INDONESIA = 'Indonesia';
    const IRELAND = 'Ireland';
    const ISRAEL = 'Israel';
    const MALAYSIA = 'Malaysia';
    const NEW_ZEALAND = 'New Zealand';
    const NIGERIA = 'Nigeria';
    const PHILIPPINES = 'Philippines';
    const POLAND = 'Poland';
    const PORTUGAL = 'Portugal';
    const SAUDI_ARABIA = 'Saudi Arabia';
    const SINGAPORE = 'Singapore';
    const SOUTH_KOREA = 'South Korea';
    const THAILAND = 'Thailand';
    const TURKEY = 'Turkey';
    const UAE = 'UAE';
    const UKRAINE = 'Ukraine';
    const VIETNAM = 'Vietnam';

    public static function getValues()
    {
        return [
            self::USA,
            self::CANADA,
            self::UK,
            self::AUSTRALIA,
            self::GERMANY,
            self::FRANCE,
            self::ITALY,
            self::JAPAN,
            self::CHINA,
            self::INDIA,
            self::BRAZIL,
            self::SOUTH_AFRICA,
            self::MEXICO,
            self::RUSSIA,
            self::SPAIN,
            self::NETHERLANDS,
            self::SWITZERLAND,
            self::SWEDEN,
            self::NORWAY,
            self::FINLAND,
            self::ARGENTINA,
            self::BELGIUM,
            self::CHILE,
            self::COLOMBIA,
            self::DENMARK,
            self::EGYPT,
            self::GREECE,
            self::INDONESIA,
            self::IRELAND,
            self::ISRAEL,
            self::MALAYSIA,
            self::NEW_ZEALAND,
            self::NIGERIA,
            self::PHILIPPINES,
            self::POLAND,
            self::PORTUGAL,
            self::SAUDI_ARABIA,
            self::SINGAPORE,
            self::SOUTH_KOREA,
            self::THAILAND,
            self::TURKEY,
            self::UAE,
            self::UKRAINE,
            self::VIETNAM,
        ];
    }

    public static function isValid($value)
    {
        return in_array($value, self::getValues());
    }
}
