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

/**
 * Table tl_voucher_card
 */
$GLOBALS['TL_DCA']['tl_voucher_card'] = array(

    // Config
    'config'      => array(
        'dataContainer'    => 'Table',
        'ctable'           => array('tl_voucher_gift'),
        'enableVersioning' => true,
        'sql'              => array(
            'keys' => array(
                'id' => 'primary'
            )
        ),
    ),    
    'list'        => array(
        'sorting'         => array(
            'mode'        => 1,            
            'fields'      => array('title'),
            'flag'        => 12,
            'panelLayout' => 'filter;search,limit'
        ),
        'label'             => array(
            'fields' => array('title','credit','type'),
            'showColumns'             => true
        ),
        'global_operations' => array(
            'all' => array(
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations'        => array(
            'edit' => array
			(
				'href'                => 'table=tl_voucher_gift',
				'icon'                => 'edit.svg'
			),
			'editheader' => array
			(
				'href'                => 'act=edit',
				'icon'                => 'header.svg'
			),            
            'show'   => array(
                'href'       => 'act=show',
                'icon'       => 'show.gif',
                'attributes' => 'style="margin-right:3px"'
            ),
        )
    ),
    // Palettes
    'palettes'    => array(
        'default'      => '{card_legend},title,type,credit,expiration,single;{sms_legend},smsGatewayID;{note_legend:hide},note'
    ),   
    // Fields
    'fields'      => array(
        'id'             => array(
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp'         => array(
            'flag' => 6,
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'title'          => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'credit' => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('rgxp' => 'natural', 'mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ),
        'type'    => array(
            'inputType' => 'select',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'reference' => $GLOBALS['TL_LANG']['tl_voucher_card'],
            'options'   => array('food', 'book','pool'),
            'eval'      => array('disabled'=>false,'includeBlankOption' => true, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'expiration' => array(
            'inputType' => 'text',
            'default'   => 30,
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('rgxp' => 'natural','mandatory' => true, 'maxlength' => 4, 'tl_class' => 'w50'),
            'sql'       => "int(4) unsigned NOT NULL default '30'"
        ),
        'single' => array
		(
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
        'note'  => array(
            'inputType' => 'textarea',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'eval'      => array('rte' => 'tinyMCE', 'tl_class' => 'clr'),
            'sql'       => 'text NULL'
        ),
        'smsGatewayID'          => array(
            'inputType' => 'select',
            'foreignKey'=> 'tl_voucher_sms_gateway.title',            
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,            
            'eval'      => array('chosen'=>true, 'mandatory'=>true, 'includeBlankOption'=>true,'multiple'=>false, 'submitOnChange'=>true, 'fieldType'=>'select', 'foreignTable'=>'tl_voucher_sms_gateway', 'titleField'=>'title', 'tl_class' => 'w50'),
            'relation'  => array('type'=>'belongsTo', 'load'=>'lazy'),
            'sql'       => "varchar(255) NULL default ''"
        ),
        'smsFormat'  => array(
            'inputType' => 'textarea',                                
            'eval'      => array('tl_class' => 'clr'),
            'sql'       => 'text NULL'
        ),
    )
);

/**
 * Class tl_voucher_card
 */
class tl_voucher_card extends Backend
{
    /**
     * @param $arrButtons
     * @param  DC_Table $dc
     * @return mixed
     */
    public function buttonsCallback($arrButtons, DC_Table $dc)
    {
        if (Input::get('act') === 'edit')
        {
            $arrButtons['customButton'] = '<button type="submit" name="customButton" id="customButton" class="tl_submit customButton" accesskey="x">' . $GLOBALS['TL_LANG']['tl_voucher_card']['customButton'] . '</button>';
        }

        return $arrButtons;
    }

}
