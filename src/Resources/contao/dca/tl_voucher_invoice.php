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

use Contao\Backend;
use Contao\DC_Table;
use Contao\Input;
//use Contao\DataContainer;

use Respinar\ContaoVoucherBundle\Model\VoucherAcceptorModel;
use Respinar\ContaoVoucherBundle\Model\VoucherStaffModel;
use Respinar\ContaoVoucherBundle\Model\VoucherCardModel;
use Respinar\ContaoVoucherBundle\Model\VoucherGiftModel;


/**
 * Table tl_voucher_invoice
 */
$GLOBALS['TL_DCA']['tl_voucher_invoice'] = array(

    // Config
    'config'      => array(
        'dataContainer'    => 'Table',
        'closed'           => true,
		'notEditable'      => true,
		'notCopyable'      => true,           
        'sql'              => array(
            'keys' => array(
                'id' => 'primary'
            )
        )
    ),    
    'list'        => array(
        'sorting'         => array(
            'mode'        => 1,
            'fields'      => array('tstamp'),
            'panelLayout' => 'filter;sort,search,limit'
        ),
        'label'             => array(
            'fields'      => array('tstamp','datetime','giftCode', 'pid','invoice','companyShare','staffShare'),
            'showColumns'             => true,
			'label_callback'          => array('tl_voucher_invoice', 'titles')
        ),
        'global_operations' => array(
            'all' => array
			(
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
        ),
        'operations'        => array(           
			'show' => array
			(
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
        )
    ),    
    // Fields
    'fields'      => array(
        'id'             => array(
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array(
            'foreignKey'=> 'tl_voucher_acceptor.title',
            'search'    => true,
            'filter'    => true,
            'sql'       => "int(10) unsigned NOT NULL"
        ),
        'tstamp'         => array(
            'filter'                  => true,
			'sorting'                 => true,
            'flag'                    => 6,
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'giftCode'  => array(
            'search'    => true,
            'sql'       => "varchar(20) NOT NULL default ''"
        ),
        'datetime'          => array(
            'search'    => true,
            'sorting'   => true,
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'giftCredit'  => array(
            'sorting'   => true,
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ),
        'invoice'  => array(
            'sorting'   => true,
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ),
        'staffShare'  => array(
            'search'    => true,
            'sorting'   => true,
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ),       
        'companyShare'  => array(
            'search'    => true,
            'sorting'   => true,
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ),
        'trackingCode'  => array(            
            'search'    => true,
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ),
        'staffID' => array(
            'foreignKey'=> 'tl_voucher_staff.name',
            'search'    => true,
            'filter'    => true,
            'sql'       => "int(10) unsigned NULL"
        ),
        'cardID' => array(
            'foreignKey'=> 'tl_voucher_card.title',
            'search'    => true,
            'filter'    => true,
            'sql'       => "int(10) unsigned NULL"
        ),
        'status' => array
		(
            'filter'    => true,
            'reference' => $GLOBALS['TL_LANG']['tl_voucher_invoice'],
			'sql'       => "char(20) NOT NULL default ''"
		)
    )
);

/**
 * Class tl_voucher_invoice
 * 
 */
class tl_voucher_invoice extends Backend
{
   
    /**
	 * Add an image to each record
	 *
	 * @param array         $row
	 * @param string        $label
	 * @param DataContainer $dc
	 * @param array         $args
	 *
	 * @return array
	 */
	public function titles($row, $label, DataContainer $dc, $args)
	{
        
        $objAcceptor = VoucherAcceptorModel::findBy('id',$row['pid']);        

        $args[3] = $objAcceptor->title;

		return $args;
	}

}
