<?php

namespace xoapp\advancedban\utils;

use pocketmine\player\Player;
use pocketmine\Server;

class Utils
{

    private static function getPlayers(): array
    {
        return Server::getInstance()->getOnlinePlayers();
    }

    public static function globalMessage(string $message): void
    {
        Server::getInstance()->broadcastMessage($message);
    }

    public static function getPlayerByPrefix(string $key): ?Player
    {
        $found = null;
        $key = strtolower($key);
        $delta = PHP_INT_MAX;
        foreach (self::getPlayers() as $player) {
            if (stripos($player->getName(), $key) === 0) {
                $curDelta = strlen($player->getName()) - strlen($key);
                if ($curDelta < $delta) {
                    $found = $player;
                    $delta = $curDelta;
                }
                if ($curDelta <= 0) {
                    break;
                }
            }
        }
        return $found;
    }
}