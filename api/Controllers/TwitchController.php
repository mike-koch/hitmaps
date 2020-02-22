<?php

namespace Controllers;


use BusinessLogic\Twitch\TwitchStreamRetriever;

class TwitchController {
    private $rouletteRivalsStreamRetriever;

    public function __construct(TwitchStreamRetriever $rouletteRivalsStreamRetriever) {
        $this->rouletteRivalsStreamRetriever = $rouletteRivalsStreamRetriever;
    }

    public function getCurrentStreams() {
        return $this->rouletteRivalsStreamRetriever->getCurrentStreams();
    }
}