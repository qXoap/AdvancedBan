<?php

namespace xoapp\advancedban\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use xoapp\advancedban\data\PermanentlyData;
use xoapp\advancedban\data\TemporarilyData;
use xoapp\advancedban\time\TimeFormatter;
use xoapp\advancedban\utils\MessageUtils;

class BanInfoCommand extends Command
{

    public function __construct()
    {
        parent::__construct("baninfo");
        $this->setPermission("baninfo.command");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): void
    {
        if (!$this->testPermissionSilent($player)) {
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage("§cUsage: /baninfo <player>");
            return;
        }

        $permanently_data = PermanentlyData::getInstance();
        $temporarily_data = TemporarilyData::getInstance();

        if ($permanently_data->exists($args[0])) {
            $banned_data = $permanently_data->getData($args[0]);

            $params = [
                "username" => $args[0],
                "duration" => "Permanently",
                "reason" => $banned_data["reason"],
                "sender" => $banned_data["sender"],
                "date" => $banned_data["date"]
            ];

            $player->sendMessage(
                MessageUtils::getMessage("permanently", "player_info", $params)
            );
            return;
        }

        if ($temporarily_data->exists($args[0])) {
            $banned_data = $temporarily_data->getData($args[0]);

            $params = [
                "username" => $args[0],
                "duration" => TimeFormatter::getTimeLeft($banned_data["duration"]),
                "reason" => $banned_data["reason"],
                "sender" => $banned_data["sender"],
                "date" => $banned_data["date"]
            ];

            $player->sendMessage(
                MessageUtils::getMessage("temporarily", "player_info", $params)
            );
            return;
        }

        $player->sendMessage(
            MessageUtils::getMessage("error", "player_not_found")
        );
    }
}