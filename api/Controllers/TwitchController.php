<?php

namespace Controllers;


use BusinessLogic\Twitch\RouletteRivalsStreamRetriever;

class TwitchController {
    private $rouletteRivalsStreamRetriever;

    public function __construct(RouletteRivalsStreamRetriever $rouletteRivalsStreamRetriever) {
        $this->rouletteRivalsStreamRetriever = $rouletteRivalsStreamRetriever;
    }

    public function getRouletteRivalsStreamers() {
        return $this->rouletteRivalsStreamRetriever->getRouletteRivalsStreamers();
    }
}