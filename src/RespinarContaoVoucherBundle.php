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

namespace Respinar\ContaoVoucherBundle;

use Respinar\ContaoVoucherBundle\DependencyInjection\RespinarContaoVoucherExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use Respinar\ContaoVoucherBundle\Model\AcceptorModel;


/**
 * Class RespinarContaoVoucherBundle
 */
class RespinarContaoVoucherBundle extends Bundle
{
	public function getContainerExtension(): RespinarContaoVoucherExtension
	{
		return new RespinarContaoVoucherExtension();
	}

	/**
	 * {@inheritdoc}
	 */
	public function build(ContainerBuilder $container): void
	{
		parent::build($container);
		
	}
}
