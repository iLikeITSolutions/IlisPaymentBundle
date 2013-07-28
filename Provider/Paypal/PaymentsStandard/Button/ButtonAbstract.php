<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Provider\Paypal\PaymentsStandard\Button;

abstract class ButtonAbstract
{
    const CMD_XCLICK = '_xclick';
    /**
     * @var string
     */
    protected $cmd;

    /**
     * @var string
     */
    protected $bn;

    /**
     * @return string
     */
    public function getCmd()
    {
        return $this->cmd;
    }

    /**
     * @return string
     */
    public function getBn()
    {
        return $this->bn;
    }

    /**
     * @param $service
     * @param string $country
     * @return string
     */
    protected function buildBn($service, $country = 'ES')
    {
        return 'Ilis_'.$service.'_WPS_'.$country;
    }
}