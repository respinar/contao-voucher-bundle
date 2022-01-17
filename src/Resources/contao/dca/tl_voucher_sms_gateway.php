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
 * Table tl_voucher_sms_gateway
 */
$GLOBALS['TL_DCA']['tl_voucher_sms_gateway'] = array(

    // Config
    'config'      => array(
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'notCopyable'      => true,  
        'sql'              => array(
            'keys' => array(
                'id' => 'primary'
            )
        ),
    ),   
    'list'        => array(
        'sorting'         => array(
            'mode'        => 2,
            'fields'      => array('title'),
            'flag'        => 1,
            'panelLayout' => 'filter;sort,search,limit'
        ),
        'label'             => array(
            'fields' => array('title','number'),
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
                'label' => &$GLOBALS['TL_LANG']['tl_voucher_sms_gateway']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),
            'delete' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_voucher_sms_gateway']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show'   => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_voucher_sms_gateway']['show'],
                'href'       => 'act=show',
                'icon'       => 'show.gif',
                'attributes' => 'style="margin-right:3px"'
            ),
        )
    ),
    // Palettes
    'palettes'    => array(
        'default'      => '{title_legend},title;{config_legend},gateway,fromNumber,username,password;'
    ),   
    // Fields
    'fields'      => array(
        'id'             => array(
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp'         => array(
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'title'          => array(
            'inputType' => 'text',
            'search'    => true,
            'flag'      => 1,
            'eval'      => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'gateway' => array(
            'inputType' => 'text',
            'flag'      => 1,
            'eval'      => array('mandatory' => true, 'unique'=>true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'fromNumber'  => array(
            'inputType' => 'text',
            'flag'      => 1,
            'eval'      => array('mandatory' => true, 'unique'=>true, 'maxlength' => 50, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'username'  => array(
            'inputType' => 'text',
            'flag'      => 1,
            'eval'      => array('mandatory' => true, 'unique'=>true, 'maxlength' => 50, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'password'  => array(
            'inputType' => 'text',
            'flag'      => 1,
            'eval'      => array('mandatory' => true, 'unique'=>true, 'maxlength' => 50, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        )
    )
);

/**
 * Class tl_voucher_sms_gateway
 */
class tl_voucher_sms_gateway extends Backend
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
            $arrButtons['customButton'] = '<button type="submit" name="customButton" id="customButton" class="tl_submit customButton" accesskey="x">' . $GLOBALS['TL_LANG']['tl_voucher_sms_gateway']['customButton'] . '</button>';
        }

        return $arrButtons;
    }

}
