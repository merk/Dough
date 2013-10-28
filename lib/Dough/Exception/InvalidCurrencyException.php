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
 * An exception that is thrown when the currency code is unknown.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class InvalidCurrencyException extends \InvalidArgumentException implements Exception
{
}
