<?php

declare(strict_types=1);

/*
 * This file is part of Voucher manager.
 * 
 * (c) Hamid Abbaszadeh 2021 <abbaszadeh.h@gmail.com>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/respinar/contao-voucher-bundle
 */

namespace Respinar\ContaoVoucherBundle\Model;

use Respinar\ContaoVoucherBundle\Model\AcceptorModel;


use Contao\Model;

/**
 * Class VoucherModel
 *
 * @package Respinar\ContaoVoucherBundle\Model
 */
class VoucherModel extends Model
{
    protected static $strTable = 'tl_voucher';

}

