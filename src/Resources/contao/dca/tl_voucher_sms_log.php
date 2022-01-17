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

/**
 * Table tl_voucher_sms_log
 */
$GLOBALS['TL_DCA']['tl_voucher_sms_log'] = array(

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
            'fields'      => array('tstamp','datetime','giftCode','acceptorCode','invoice','ip'),
            'showColumns'             => true,
			//'label_callback'          => array('tl_voucher_sms_log', 'titles')
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
        'tstamp'         => array(            
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'fromNumber'  => array(
            'sql' => "varchar(15) NULL"
        ),
        'toNumber'  => array(
            'sql' => "varchar(15) NULL"
        ),
        'message'  => array(            
            'sql'       => 'text NULL'
        ),
        'status' => array
		(            
			'sql' => "char(2) NULL"
		)
    )
);

/**
 * Class tl_voucher_sms_log
 * 
 */
class tl_voucher_sms_log extends Backend
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

        $args[2] = $objCard->title;        
        $args[3] = $objAcceptor->title;

		return $args;
	}

}
