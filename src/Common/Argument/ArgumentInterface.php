<?php
/*
 * This file is part of the slince/tuzki package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Slince\Tuzki\Common\Argument;

interface ArgumentInterface
{
    /**
     * 参数名称
     * @return string
     */
    public function getName();

    /**
     * 参数类型，gettype 的值
     * @return string
     */
    public function getType();

    /**
     * 参数解释
     *
     * @return string
     */
    public function getLabel();
}