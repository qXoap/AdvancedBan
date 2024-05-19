<?php

namespace xoapp\advancedban\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use xoapp\advancedban\data\TemporarilyData;
use xoapp\advancedban\time\TimeFormatter;
use xoapp\advancedban\utils\MessageUtils;
use xoapp\advancedban\utils\Utils;

class TempBanCommand extends Command
{

    public function __construct()
    {
        parent::__construct("tempban");
        $this->setPermission("tempban.command");
        $this->setAliases(["tban"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): void
    {
        if (!$this->testPermissionSilent($player)) {
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage("§cUsage: /tempban <player> <reason> <time>");
            return;
        }

        if (!isset($args[1])) {
            $player->sendMessage("§cUsage: /tempban <player> <reason> <time>");
            return;
        }

        if (!isset($args[2])) {
            $player->sendMessage("§cUsage: /tempban <player> <reason> <time>");
            return;
        }

        $i_player = Utils::getPlayerByPrefix($args[0]);
        $data = TemporarilyData::getInstance();

        if ($data->exists($args[0])) {
            $player->sendMessage(
                MessageUtils::getMessage("error", "player_already_banned")
            );
            return;
        }

        $string_time = TimeFormatter::intToString($args[2]);
        $int_time = TimeFormatter::stringToInt($args[2]);
        $format_time = TimeFormatter::getFormatTime($int_time, $args[2]);

        if (is_null($string_time)) {
            $player->sendMessage(
                MessageUtils::getMessage("error", "invalid_time")
            );
            return;
        }

        if (!$i_player instanceof Player) {

            $contents = [
                "username" => $args[0],
                "reason" => $args[1],
                "time" => $format_time,
                "sender" => $player->getName(),
                "date" => date("Y-m-d H:i:s")
            ];

            $params = [
                "username" => $args[0],
                "reason" => $args[1],
                "time" => $format_time,
                "sender" => $player->getName()
            ];

            $data->setData($args[0], $contents);

            $player->sendMessage(
                MessageUtils::getMessage("temporarily", "player_banned", $params)
            );

            Utils::globalMessage(
                MessageUtils::getMessage("temporarily", "global_player_banned", $params)
            );
            return;
        }

        $contents = [
            "username" => $i_player->getName(),
            "reason" => $args[1],
            "duration" => $format_time,
            "sender" => $player->getName(),
            "date" => date("Y-m-d H:i:s")
        ];

        $params = [
            "username" => $i_player->getName(),
            "reason" => $args[1],
            "duration" => $format_time,
            "sender" => $player->getName()
        ];

        $data->setData($args[0], $contents);

        $player->sendMessage(
            MessageUtils::getMessage("temporarily", "player_banned", $params)
        );

        $i_player->kick(
            MessageUtils::getMessage("temporarily", "kick_message", $params)
        );

        Utils::globalMessage(
            MessageUtils::getMessage("temporarily", "global_player_banned", $params)
        );
    }
}