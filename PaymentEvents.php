<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle;

class PaymentEvents
{
    const TRANSACTION_CREATED   = 'ilis.payment.transaction.created';
    const TRANSACTION_PROCESSED = 'ilis.payment.transaction.processed';
}