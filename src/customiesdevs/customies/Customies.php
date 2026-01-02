<?php
declare(strict_types=1);

namespace customiesdevs\customies;

use customiesdevs\customies\block\CustomiesBlockFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

final class Customies extends PluginBase {
    private static bool $registered = false;

    public static function init(PluginBase $plugin): void {
        if (self::$registered) return;

        $plugin->getServer()->getPluginManager()->registerEvents(new CustomiesListener(), $plugin);

        $cachePath = $plugin->getDataFolder() . 'idcache';
        $plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(static function () use ($cachePath): void {
            // This task is scheduled with a 0-tick delay so it runs as soon as the server has started. Plugins should
            // register their custom blocks and entities in onEnable() before this is executed.
            CustomiesBlockFactory::getInstance()->addWorkerInitHook($cachePath);
        }), 0);

        self::$registered = true;
    }
}
