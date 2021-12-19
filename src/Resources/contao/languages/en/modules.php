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

use Respinar\ContaoVoucherBundle\Controller\FrontendModule\VoucherConfrimModuleController;

/**
 * Backend modules
 */
$GLOBALS['TL_LANG']['MOD']['voucher_module'] = 'Voucher Manger';
$GLOBALS['TL_LANG']['MOD']['voucher_codes'] = ['Vochers', 'Voucher Manger'];
$GLOBALS['TL_LANG']['MOD']['voucher_acceptors'] = ['Acceptors', 'Acceptor Manger'];

/**
 * Frontend modules
 */
$GLOBALS['TL_LANG']['FMD']['voucher_confrim'] = 'Voucher Confirmation';
$GLOBALS['TL_LANG']['FMD'][VoucherConfrimModuleController::TYPE] = ['Vocher Confirmation', 'Confrimmation of Voucher'];

