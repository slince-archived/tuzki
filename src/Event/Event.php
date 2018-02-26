<?php
/*
 * This file is part of the slince/tuzki package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\Tuzki\Event;

use Slince\Event\Event as BaseEvent;

class Event extends BaseEvent
{
    /**
     * 系统初始化完毕之后触发
     * @var string
     */
    const TUZKI_INIT = 'tuzki.init';
}