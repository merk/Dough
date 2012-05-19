<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dough\Twig;

use Dough\Bank\BankInterface;
use Dough\Money\MoneyInterface;

/**
 * Provides integration of the Dough with Twig.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class DoughExtension extends \Twig_Extension
{
    private $bank;

    /**
     * Constructor.
     *
     * @param BankInterface $bank The bank
     */
    public function __construct(BankInterface $bank = null)
    {
        $this->bank = $bank;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'dough_currency' => new \Twig_Filter_Method($this, 'getAmount', array('is_safe' => array('html'))),
        );
    }

    /**
     * Gets the amount.
     *
     * @param MoneyInterface $money    A MoneyInterface instance
     * @param string         $currency The currency code
     */
    public function getAmount(MoneyInterface $money, $currency = null)
    {
        $reduced = $this->bank->reduce($money, $currency);

        return $reduced->getAmount();
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'merk_dough';
    }
}
