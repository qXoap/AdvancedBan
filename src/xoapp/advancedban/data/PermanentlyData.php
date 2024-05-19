<?php

namespace xoapp\advancedban\data;

use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use xoapp\advancedban\Loader;

class PermanentlyData
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
            Loader::getInstance()->getDataFolder() . "permanently.json", Config::JSON
        );
    }

    public function setData(string $key, array $data): void
    {
        $this->data->set($key, $data);
        $this->data->save();
    }

    public function getData(string $key): array
    {
        return $this->data->get($key);
    }

    public function unsetData(string $key): void
    {
        $this->data->remove($key);
        $this->data->save();
    }

    public function exists(string $key): bool
    {
        return $this->data->exists($key);
    }

    public function getSavedData(): array
    {
        return $this->data->getAll(true);
    }
}