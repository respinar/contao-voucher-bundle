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

use Respinar\ContaoVoucherBundle\Model\VoucherAcceptorModel;
use Respinar\ContaoVoucherBundle\Model\VoucherGiftModel;
use Respinar\ContaoVoucherBundle\Model\VoucherCardModel;
use Respinar\ContaoVoucherBundle\Model\VoucherStaffModel;
use Respinar\ContaoVoucherBundle\Model\VoucherLogModel;
use Respinar\ContaoVoucherBundle\Model\VoucherInvoiceModel;
use Respinar\ContaoVoucherBundle\Model\VoucherSMSGatewayModel;
use Respinar\ContaoVoucherBundle\Model\VoucherSMSLogModel;


/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['voucher_module'] = array (
    'voucher_card' => array( 'tables'=> array('tl_voucher_card','tl_voucher_gift')),
    'voucher_staff' => array( 'tables'=> array('tl_voucher_staff')),
    'voucher_acceptor' => array( 'tables'=> array('tl_voucher_acceptor')),
    'voucher_invoice' => array( 'tables'=> array('tl_voucher_invoice')),
    'voucher_gateway' => array( 'tables'=> array('tl_voucher_sms_gateway'))
);

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_voucher_card']        = VoucherCardModel::class;
$GLOBALS['TL_MODELS']['tl_voucher_gift']        = VoucherGiftModel::class;
$GLOBALS['TL_MODELS']['tl_voucher_staff']       = VoucherStaffModel::class;
$GLOBALS['TL_MODELS']['tl_voucher_acceptor']    = VoucherAcceptorModel::class;
$GLOBALS['TL_MODELS']['tl_voucher_invoice']     = VoucherInvoiceModel::class;
$GLOBALS['TL_MODELS']['tl_voucher_log']         = VoucherLogModel::class;
$GLOBALS['TL_MODELS']['tl_voucher_sms_gateway'] = VoucherSMSGatewayModel::class;
$GLOBALS['TL_MODELS']['tl_voucher_sms_log']     = VoucherSMSLogModel::class;