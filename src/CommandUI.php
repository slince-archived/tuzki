<?php
/**
 * Slince tuzki library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Tuzki;

use Slince\Tuzki\Command\QQCommand;
use Symfony\Component\Console\Application;

class CommandUI
{
    /**
     * 创建command
     * @return array
     */
    static function createCommands()
    {
        return [
            new QQCommand(),
        ];
    }
    /**
     * command应用主入口
     * @throws \Exception
     */
    static function main()
    {
        $application = new Application();
        $application->setAutoExit(true);
        $application->addCommands(self::createCommands());
        $application->run();
    }
}