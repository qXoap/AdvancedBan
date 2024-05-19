<?php

namespace xoapp\advancedban\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use xoapp\advancedban\data\PermanentlyData;
use xoapp\advancedban\data\TemporarilyData;

class BanListCommand extends Command
{

    public function __construct()
    {
        parent::__construct("banlist");
        $this->setPermission("banlist.command");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): void
    {
        if (!$this->testPermissionSilent($player)) {
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage("§cUsage: /banlist <permanent/temporarily>");
            return;
        }

        switch (strtolower($args[0])) {
            case "p":
            case "permanently":
                $data = PermanentlyData::getInstance()->getSavedData();

                if (count($data) <= 0) {
                    $player->sendMessage("§cThere are no banned players.");
                    return;
                }

                $values = implode(", ", array_values($data));

                $player->sendMessage("§aThere are " . count($data) . " banned players: " . $values);
                break;

            case "t":
            case "temporarily":
                $data = TemporarilyData::getInstance()->getSavedData();

                if (count($data) <= 0) {
                    $player->sendMessage("§cThere are no banned players.");
                    return;
                }

                $values = implode(", ", array_values($data));

                $player->sendMessage("§aThere are " . count($data) . " banned players: " . $values);
                break;

            default:
                $player->sendMessage("§cUsage: /banlist <permanent/temporarily>");
        }
    }
}