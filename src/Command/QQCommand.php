<?php
/**
 * Slince tuzki library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Tuzki\Command;

use Slince\Tuzki\Cogitation\ItpkCogitation;
use Slince\Tuzki\Cogitation\TulingCogitation;
use Slince\Tuzki\QQTuzki;
use Slince\Tuzki\Tuzki;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class QQCommand extends BaseCommand
{
    const NAME = 'qq';

    public function configure()
    {
        $this->setName(static::NAME);
        $this->addArgument('name', InputArgument::REQUIRED, "Tuzki name, You can call him in the group or discuss")
            ->addOption('quite', null, InputOption::VALUE_NONE, "Quite Mode, Tuzki only reply when mention his name")
            ->addOption('key', null, InputOption::VALUE_REQUIRED, "Robot key")
            ->addOption('secret', null, InputOption::VALUE_OPTIONAL, "Robot secret")
            ->addOption('cogitation', null, InputOption::VALUE_OPTIONAL, "Tuzki way of thinking", TulingCogitation::NAME)
            ->addOption('qr', null, InputOption::VALUE_OPTIONAL, "Qr code image path", getcwd() . '/qr.png');
    }

    /**
     * 执行
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $qqTuzki = $this->makeQQTuzki($input);
        $output->writeln("You will find a qr code image at [{$input->getOption('qr')}], please scan it!");
        $qqTuzki->listen();
    }

    /**
     * @param InputInterface $input
     * @return QQTuzki
     */
    protected function makeQQTuzki(InputInterface $input)
    {
        $cogitation = $input->getOption('cogitation');
        switch ($cogitation) {
            case ItpkCogitation::NAME:
                $cogitation = new ItpkCogitation($input->getOption('key'), $input->getOption('secret'));
                break;
            default:
                $cogitation = new TulingCogitation($input->getOption('key'));
                break;
        }
        $tuzki = new Tuzki($cogitation);
        $qqTuzki = new QQTuzki($input->getArgument('name'), $input->getOption('qr'), $tuzki);
        if ($input->getOption('quite')) {
            $qqTuzki->setQuiteMode(true);
        }
        return $qqTuzki;
    }
}