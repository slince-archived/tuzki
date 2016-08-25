<?php
/**
 * Slince tuzki library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Tuzki\Command;

use Slince\Tuzki\Cogitation\ItpkCogitation;
use Slince\Tuzki\Cogitation\TulingCogitation;
use Slince\Tuzki\QQTuzki;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class QQCommand extends BaseCommand
{
    protected $name = 'qq';

    function configure()
    {
        $this->addOption('quite', 'q', InputOption::VALUE_OPTIONAL, "Quite Mode, Tuzki only reply when mention his name", false)
            ->addArgument('cogitation', InputArgument::OPTIONAL, "Tuzki way of thinking", TulingCogitation::NAME)
            ->addArgument('key', InputArgument::REQUIRED, "Robot key")
            ->addArgument('secret', InputArgument::OPTIONAL, "Robot secret")
            ->addArgument('qr', InputArgument::OPTIONAL, "Qr code image path", getcwd() . '/qr.png');
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $qqTuzki = $this->makeQQTuzki($input);
        try {
            $output->writeln("You will find a qr code image at [{$input->getArgument('qr')}], please scan it!");
            $qqTuzki->listen();
        } catch (\Exception $e) {
            $output->writeln("Please try again!");
        }
    }

    /**
     * @param InputInterface $input
     * @return QQTuzki
     */
    protected function makeQQTuzki(InputInterface $input)
    {
        $cogitation = $input->getArgument('cogitation');
        switch ($cogitation) {
            case ItpkCogitation::NAME:
                $tuzki = new ItpkCogitation($input->getArgument('key'), $input->getArgument('secret'));
                break;
            default:
                $tuzki = new TulingCogitation($input->getArgument('key'));
                break;
        }
        $qqTuzki = new QQTuzki($input->getArgument('qr'), $tuzki);
        if ($input->getArgument('quite')) {
            $qqTuzki->setQuiteMode(true);
        }
        return $qqTuzki;
    }
}