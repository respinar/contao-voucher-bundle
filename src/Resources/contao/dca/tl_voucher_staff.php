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
 * Table tl_voucher_staff
 */
$GLOBALS['TL_DCA']['tl_voucher_staff'] = array(

    // Config
    'config'      => array(
        'dataContainer'    => 'Table',        
        'notCopyable'      => true,
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
            'fields'      => array('name'),
            'flag'        => 12,
            'panelLayout' => 'filter;search,limit'
        ),
        'label'             => array(
            'fields' => array('name','phone','employeeID'),
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
            'edit'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_voucher_staff']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),
            'show'   => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_voucher_staff']['show'],
                'href'       => 'act=show',
                'icon'       => 'show.gif',
                'attributes' => 'style="margin-right:3px"'
            ),
        )
    ),
    // Palettes
    'palettes'    => array(
        'default'      => '{staff_legend},name,employeeID,familyMembers;{notification_legend},phone,gatewayID;{note_legend:hide},note'
    ),   
    // Fields
    'fields'      => array(
        'id'             => array(
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp'         => array(
            'flag' => 6,
            'sql'  => "int(10) unsigned NOT NULL default '0'"
        ),
        'name'          => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'employeeID' => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('mandatory' => false, 'unique'=>true, 'maxlength' => 5, 'tl_class' => 'w50'),
            'sql'       => "int(5) unsigned NOT NULL default '0'"
        ),
        'phone' => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('mandatory' => true, 'unique'=>true, 'maxlength' => 14, 'tl_class' => 'w50'),
            'sql'       => "varchar(20) NOT NULL default ''"
        ),
        'gatewayID' => array(
            'inputType' => 'select',
            'foreignKey'=> 'tl_voucher_gateway.title',
            'flag'      => 1,
            'eval'      => array('mandatory' => true, 'includeBlankOption'=>true, 'multiple'=>false, 'fieldType'=>'select', 'foreignTable'=>'tl_voucher_gateway', 'tl_class' => 'w50'),
            'sql'       => "varchar(20) NOT NULL default ''"
        ),
        'familyMembers'    => array(
            'inputType' => 'select',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'reference' => $GLOBALS['TL_LANG']['tl_voucher_staff'],
            'options'   => array(1, 2, 3, 4, 5, 6),
            'eval'      => array('disabled'=>false,'tl_class' => 'w50'),
            'sql'       => "int(1) unsigned NOT NULL default '1'",
        ),
        'note'  => array(
            'inputType' => 'textarea',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'eval'      => array('rte' => 'tinyMCE', 'tl_class' => 'clr'),
            'sql'       => 'text NULL'
        )
    )
);

/**
 * Class tl_voucher_staff
 */
class tl_voucher_staff extends Backend
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
            $arrButtons['customButton'] = '<button type="submit" name="customButton" id="customButton" class="tl_submit customButton" accesskey="x">' . $GLOBALS['TL_LANG']['tl_voucher_staff']['customButton'] . '</button>';
        }

        return $arrButtons;
    }
}
