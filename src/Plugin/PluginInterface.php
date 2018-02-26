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

use Symfony\Component\OptionsResolver\OptionsResolver;

interface PluginInterface
{
    /**
     * 设置运行参数，兔斯基会在启动之前询问
     *
     * @param OptionsResolver $resolver
     */
    public function getRequiredArguments(OptionsResolver $resolver);

    /**
     * 绑定事件
     * @param $eventName
     */
    public function on($eventName);
}