<?php

namespace App\DataFixtures\Helpers;
class Randomizer {
    const NAMES = [
        'Terrence Babcock','Katarina Ybarra','Keyshawn Outlaw','Dahlia Edmonds','Trevor Sager','Deborah Velazquez','Johnny Kelsey',
        'Saul Earle','Denver Dayton','Cedrick Finn','Emmanuel Lawler','Rocco Brennan','Claudio Southard','Kalia Ireland','Brandan Shields',
        'Mohammed Greiner','Ayana Medley','Augustine Doan','Bowen Kasper','Myranda Witt','Kyler Flores','Deonte Stull','Axel Zaragoza','Violet Byrnes',
        'Halie Christopher','Treyvon Belcher','Menachem Edgar','Emerald Goodwin','Elmer Beckwith','Kellie Stewart','Alena Gipson','Keyshawn Goforth',
        'Travon Oglesby','Anyssa Simmons','Brionna Rainey','Erick Ling','Caylin Paris','Baylie Noll','Courtney Lomeli','Shyla Wong','Orion Weinberg',
        'Brandon Hollis','Ismael Shumate','Meaghan Adkins','Celeste Fallon','Libby Branson','Sunny Christenson','Korbin Carl','Annamarie Archer',
        'Jovanny McGovern'
    ];

    public static function getDateTime(\DateTime $from) {
        $int = rand($from->getTimestamp(), time());
        return (new \DateTime())->setTimestamp($int);
    }

    public static function getName() {
        return self::NAMES[array_rand(self::NAMES)];
    }


}
