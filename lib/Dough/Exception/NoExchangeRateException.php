<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dough\Exception;

/**
 * An exception that is thrown when no currency conversion rate can be found.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class NoExchangeRateException extends \InvalidArgumentException implements Exception
{

}
