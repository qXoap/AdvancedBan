<?php

namespace xoapp\advancedban\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use xoapp\advancedban\data\PermanentlyData;
use xoapp\advancedban\utils\MessageUtils;
use xoapp\advancedban\utils\Utils;

class BanCommand extends Command
{

    public function __construct()
    {
        parent::__construct("ban");
        $this->setPermission("ban.command");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): void
    {
        if (!$this->testPermissionSilent($player)) {
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage("§cUsage: /ban <player> <reason>");
            return;
        }

        if (!isset($args[1])) {
            $player->sendMessage("§cUsage: /ban <player> <reason>");
            return;
        }

        $i_player = Utils::getPlayerByPrefix($args[0]);

        $data = PermanentlyData::getInstance();

        if (!$i_player instanceof Player) {
            if ($data->exists($args[0])) {
                $player->sendMessage(
                    MessageUtils::getMessage("error", "player_already_banned")
                );
                return;
            }

            $contents = [
                "username" => $args[0],
                "reason" => $args[1],
                "sender" => $player->getName(),
                "date" => date("Y-m-d H:i:s")
            ];

            $params = [
                "username" => $args[0],
                "duration" => "Permanently",
                "reason" => $args[1],
                "sender" => $player->getName(),
            ];

            $data->setData($args[0], $contents);
            $player->sendMessage(
                MessageUtils::getMessage("permanently", "player_banned", $params)
            );

            Utils::globalMessage(
                MessageUtils::getMessage("permanently","global_player_banned", $params)
            );
            return;
        }

        if ($data->exists($i_player->getName())) {
            $player->sendMessage(
                MessageUtils::getMessage("error", "player_already_banned")
            );
            return;
        }

        $contents = [
            "username" => $i_player->getName(),
            "reason" => $args[1],
            "sender" => $player->getName(),
            "date" => date("Y-m-d H:i:s")
        ];

        $params = [
            "username" => $i_player->getName(),
            "duration" => "Permanently",
            "reason" => $args[1],
            "sender" => $player->getName(),
        ];

        $data->setData($i_player->getName(), $contents);
        $player->sendMessage(
            MessageUtils::getMessage("permanently", "player_banned", $params)
        );

        $i_player->kick(
            MessageUtils::getMessage("permanently", "kick_message", $params)
        );

        Utils::globalMessage(
            MessageUtils::getMessage("permanently", "global_player_banned", $params)
        );
    }
}