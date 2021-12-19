<?php

/*
 * This file is part of Voucher manager.
 * 
 * (c) Hamid Abbaszadeh 2021 <abbaszadeh.h@gmail.com>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/respinar/contao-voucher-bundle
 */

use Respinar\ContaoVoucherBundle\EventListener\StoreFormDataListener;
use Respinar\ContaoVoucherBundle\EventListener\PrepareFormDataListener;

use Respinar\ContaoVoucherBundle\Model\AcceptorModel;
use Respinar\ContaoVoucherBundle\Model\VoucherModel;



/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['voucher_module']['voucher_codes'] = array(
    'tables' => array('tl_voucher')
);
$GLOBALS['BE_MOD']['voucher_module']['voucher_acceptors'] = array(
    'tables' => array('tl_acceptor')
);

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_voucher'] = VoucherModel::class;
$GLOBALS['TL_MODELS']['tl_acceptor'] = AcceptorModel::class;


//$GLOBALS['TL_HOOKS']['storeFormData'][] = [StoreFormDataListener::class, '__invoke'];
$GLOBALS['TL_HOOKS']['prepareFormData'][] = [PrepareFormDataListener::class, '__invoke'];
