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
 * Table tl_voucher_log
 */
$GLOBALS['TL_DCA']['tl_voucher_log'] = array(

    // Config
    'config'      => array(
        'dataContainer'    => 'Table',
        'closed'                      => true,
		'notEditable'                 => true,
		'notCopyable'                 => true,           
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
            'fields'      => array('tstamp','datetime','giftCode', 'acceptorID','invoice','status'),
            'showColumns'             => true,
			//'label_callback'          => array('tl_voucher_log', 'titles')
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
            'delete' => array
			(
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"'
			),
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
        'tstamp'         => array(
            'filter'                  => true,
			'sorting'                 => true,
            'flag'                    => 6,
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'giftCode'  => array(
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'sql'       => "varchar(20) NOT NULL default ''"
        ),
        'acceptorCode'  => array(
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,          
            'sql'       => "varchar(20) NOT NULL default ''"
        ),
        'datetime'          => array(
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'invoice'  => array(
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'sql'       => "varchar(20) NULL default ''"
        ),
        'acceptorID' => array(
            'foreignKey'=> 'tl_voucher_acceptor.title',
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'sql'       => "varchar(255) NULL default ''"
        ),
        'status' => array
		(
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'reference' => $GLOBALS['TL_LANG']['tl_voucher_log'],
			'sql'       => "char(20) NOT NULL default ''"
		),
    )
);

/**
 * Class tl_voucher_log
 * 
 */
class tl_voucher_log extends Backend
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

        $objAcceptor = VoucherAcceptorModel::findBy('id',$row['acceptorID']);        
        $objStaff = VoucherStaffModel::findBy('id',$row['staffID']);

        $args[1] = $objCard->title;
        $args[4] = $objStaff->name;
        $args[5] = $objAcceptor->title;

		return $args;
	}

}
