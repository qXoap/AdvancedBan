<?php

namespace xoapp\advancedban;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use xoapp\advancedban\commands\BanCommand;
use xoapp\advancedban\commands\BanInfoCommand;
use xoapp\advancedban\commands\BanListCommand;
use xoapp\advancedban\commands\KickCommand;
use xoapp\advancedban\commands\TempBanCommand;
use xoapp\advancedban\commands\UnBanCommand;

class Loader extends PluginBase
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }

    protected function onEnable(): void
    {
        self::setInstance($this);

        $this->unregisterCommands(["ban", "banlist", "unban", "kick"]);

        $this->saveResources(["config.yml", "messages.json"]);

        $this->initCommands();

        $this->getServer()->getPluginManager()->registerEvents(new EventHandler(), $this);
    }

    private function saveResources(array $files): void
    {
        foreach ($files as $file) {
            if (!file_exists($this->getDataFolder() . $file)) {
                $this->saveResource($file);
            }
        }
    }

    private function initCommands(): void
    {
        $command_map = $this->getServer()->getCommandMap();

        $command_map->registerAll("advancedban", [
            new BanCommand(),
            new BanListCommand(),
            new UnBanCommand(),
            new TempBanCommand(),
            new BanInfoCommand(),
            new KickCommand()
        ]);
    }

    private function unregisterCommands(array $keys): void
    {
        $command_map = $this->getServer()->getCommandMap();

        foreach ($keys as $key) {
            if (!is_null($command_map->getCommand($key))) {

                $command_map->unregister(
                    $command_map->getCommand($key)
                );
            }
        }
    }
}