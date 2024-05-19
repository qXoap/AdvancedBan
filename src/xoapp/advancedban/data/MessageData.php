<?php

namespace xoapp\advancedban\data;

use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use xoapp\advancedban\Loader;

class MessageData
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }

    private Config $data;

    public function __construct()
    {
        self::setInstance($this);

        $this->data = new Config(
            Loader::getInstance()->getDataFolder() . "messages.json", Config::JSON
        );
    }

    public function getData(string $key): array
    {
        return $this->data->get($key);
    }
}