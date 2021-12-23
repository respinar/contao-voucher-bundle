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
 * Frontend modules
 */
$GLOBALS['TL_DCA']['tl_module']['palettes'][VoucherValidateModuleController::TYPE] = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';