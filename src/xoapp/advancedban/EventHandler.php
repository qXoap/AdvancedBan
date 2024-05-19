<?php

namespace xoapp\advancedban;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use xoapp\advancedban\data\PermanentlyData;
use xoapp\advancedban\data\TemporarilyData;
use xoapp\advancedban\time\TimeFormatter;
use xoapp\advancedban\utils\MessageUtils;

class EventHandler implements Listener
{

    public function onPlayerLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();

        $permanently_data = PermanentlyData::getInstance();
        $temporarily_data = TemporarilyData::getInstance();

        if ($permanently_data->exists($player->getName())) {
            $banned_data = $permanently_data->getData($player->getName());

            $params = [
                "username" => $banned_data["username"],
                "reason" => $banned_data["reason"],
                "sender" => $banned_data["sender"],
                "date" => $banned_data["date"],
                "duration" => "Permanently"
            ];

            $player->kick(
                MessageUtils::getMessage("permanently", "kick_message", $params)
            );
            return;
        }

        if ($temporarily_data->exists($player->getName())) {
            $banned_data = $temporarily_data->getData($player->getName());

            $params = [
                "{username}" => $banned_data["username"],
                "{reason}" => $banned_data["reason"],
                "{sender}" => $banned_data["sender"],
                "{date}" => $banned_data["date"],
                "{duration}" => TimeFormatter::getTimeLeft($banned_data["duration"])
            ];

            if ($params["{duration}"] <= 0) {
                $temporarily_data->unsetData($player->getName());
                return;
            }

            $player->kick(
                MessageUtils::getMessage("temporarily", "kick_message", $params)
            );
        }
    }
}