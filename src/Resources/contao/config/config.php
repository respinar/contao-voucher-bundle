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

//use Respinar\ContaoVoucherBundle\EventListener\StoreFormDataListener;
//use Respinar\ContaoVoucherBundle\EventListener\PrepareFormDataListener;
//use Respinar\ContaoVoucherBundle\EventListener\ValidateFormFieldListener;
use Respinar\ContaoVoucherBundle\EventListener\ProcessFormDataListener;

use Respinar\ContaoVoucherBundle\Model\VoucherAcceptorModel;
use Respinar\ContaoVoucherBundle\Model\VoucherGiftModel;
use Respinar\ContaoVoucherBundle\Model\VoucherCardModel;
use Respinar\ContaoVoucherBundle\Model\VoucherStaffModel;



/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['voucher_module'] = array (
    'voucher_gift' => array( 'tables'=> array('tl_voucher_gift')),
    'voucher_card' => array( 'tables'=> array('tl_voucher_card')),
    'voucher_staff' => array( 'tables'=> array('tl_voucher_staff')),
    'voucher_acceptor' => array( 'tables'=> array('tl_voucher_acceptor')),
    'voucher_log' => array( 'tables'=> array('tl_voucher_log'))
);

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_voucher_card']     = VoucherCardModel::class;
$GLOBALS['TL_MODELS']['tl_voucher_gift']     = VoucherGiftModel::class;
$GLOBALS['TL_MODELS']['tl_voucher_staff']    = VoucherStaffModel::class;
$GLOBALS['TL_MODELS']['tl_voucher_acceptor'] = VoucherAcceptorModel::class;


//$GLOBALS['TL_HOOKS']['storeFormData'][] = [StoreFormDataListener::class, '__invoke'];
//$GLOBALS['TL_HOOKS']['prepareFormData'][] = [PrepareFormDataListener::class, '__invoke'];
//$GLOBALS['TL_HOOKS']['validateFormField'][] = [ValidateFormFieldListener::class, '__invoke'];
$GLOBALS['TL_HOOKS']['processFormData'][] = [ProcessFormDataListener::class, '__invoke'];
