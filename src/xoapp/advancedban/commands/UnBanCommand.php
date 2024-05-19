<?php

namespace xoapp\advancedban\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use xoapp\advancedban\data\PermanentlyData;
use xoapp\advancedban\data\TemporarilyData;
use xoapp\advancedban\utils\MessageUtils;

class UnBanCommand extends Command
{

    public function __construct()
    {
        parent::__construct("unban");
        $this->setPermission("unban.command");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): void
    {
        if (!$this->testPermissionSilent($player)) {
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage("§cUsage: /unban <player>");
            return;
        }

        $permanently_data = PermanentlyData::getInstance();
        $temporarily_data = TemporarilyData::getInstance();

        if ($permanently_data->exists($args[0])) {

            $permanently_data->unsetData($args[0]);

            $player->sendMessage(
                MessageUtils::getMessage("permanently", "player_unbanned", ["username" => $args[0]])
            );
            return;
        }

        if ($temporarily_data->exists($args[0])) {

            $temporarily_data->unsetData($args[0]);

            $player->sendMessage(
                MessageUtils::getMessage("temporarily", "player_unbanned", ["username" => $args[0]])
            );
            return;
        }

        $player->sendMessage(
            MessageUtils::getMessage("error", "player_not_found")
        );
    }
}