<?php

namespace xoapp\advancedban\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use xoapp\advancedban\utils\MessageUtils;
use xoapp\advancedban\utils\Utils;

class KickCommand extends Command
{

    public function __construct()
    {
        parent::__construct("kick");
        $this->setPermission("kick.command");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): void
    {
        if (!$this->testPermissionSilent($player)) {
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage("§cUsage: /kick <player> <reason>");
            return;
        }

        if (!isset($args[1])) {
            $player->sendMessage("§cUsage: /kick <player> <reason>");
            return;
        }

        $i_player = Utils::getPlayerByPrefix($args[0]);

        if (!$i_player instanceof Player) {
            $player->sendMessage(
                MessageUtils::getMessage("global", "player_not_found")
            );
            return;
        }

        $params = [
            "sender" => $player->getName(),
            "reason" => $args[1]
        ];

        $i_player->kick(
            MessageUtils::getMessage("global", "kick_message", $params)
        );

        $player->sendMessage("§7You kicked §e" . $i_player->getName() . "§7!");
    }
}