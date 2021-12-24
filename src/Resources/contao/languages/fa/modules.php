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
$GLOBALS['TL_LANG']['MOD']['voucher_module'] = 'مدیریت کارت هدیه';
$GLOBALS['TL_LANG']['MOD']['voucher_gift'] = ['کارت هدیه‌ها', 'Gift Manger'];
$GLOBALS['TL_LANG']['MOD']['voucher_card'] = ['کارت هدیه‌ها', 'Card Manger'];
$GLOBALS['TL_LANG']['MOD']['voucher_staff'] = ['کارمندان', 'Staff Manger'];
$GLOBALS['TL_LANG']['MOD']['voucher_acceptor'] = ['پذیرنده‌ها', 'Acceptor Manger'];
$GLOBALS['TL_LANG']['MOD']['voucher_log'] = ['گزارش', 'View logs'];

/**
 * Frontend modules
 */
$GLOBALS['TL_LANG']['FMD']['voucher_validate'] = 'کارت هدیه';
$GLOBALS['TL_LANG']['FMD'][VoucherValidateModuleController::TYPE] = ['تایید کارت هدیه', 'Validation of Gift Card'];

