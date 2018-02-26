<?php
/*
 * This file is part of the slince/tuzki package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\Tuzki\Plugin;

use Doctrine\Common\Collections\ArrayCollection;
use Slince\Event\DispatcherInterface;
use Slince\Tuzki\Tuzki;

class PluginManager extends ArrayCollection
{
    /**
     * @var Tuzki
     */
    protected $tuzki;

    /**
     * @var DispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var ArrayCollection
     */
    protected $arguments;

    public function __construct(DispatcherInterface $dispatcher, $arguments)
    {
        $this->eventDispatcher = $dispatcher;
        $this->arguments = $arguments;
    }

    /**
     * 安装插件
     * @param PluginInterface $plugin
     */
    public function install(PluginInterface $plugin)
    {
        $this->arguments->
        $this->add($plugin);
    }
}