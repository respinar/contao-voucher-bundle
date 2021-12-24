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

use Respinar\ContaoVoucherBundle\Controller\FrontendModule\VoucherValidateModuleController;

/**
 * Backend modules
 */
$GLOBALS['TL_LANG']['MOD']['voucher_module'] = 'Voucher Manger';
$GLOBALS['TL_LANG']['MOD']['voucher_gift'] = ['Gifts', 'Gift Manger'];
$GLOBALS['TL_LANG']['MOD']['voucher_card'] = ['Gift Cards', 'Card Manger'];
$GLOBALS['TL_LANG']['MOD']['voucher_staff'] = ['Staffs', 'Staff Manger'];
$GLOBALS['TL_LANG']['MOD']['voucher_acceptor'] = ['Acceptors', 'Acceptor Manger'];
$GLOBALS['TL_LANG']['MOD']['voucher_log'] = ['Voucher logs', 'View logs'];

/**
 * Frontend modules
 */
$GLOBALS['TL_LANG']['FMD']['voucher_validate'] = 'Voucher Validate';
$GLOBALS['TL_LANG']['FMD'][VoucherValidateModuleController::TYPE] = ['Voucher Validation', 'Validation of Gift Card'];

