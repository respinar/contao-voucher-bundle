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

use Respinar\ContaoVoucherBundle\Model\VoucherAcceptorModel;


/**
 * Table tl_voucher_gift
 */
$GLOBALS['TL_DCA']['tl_voucher_gift'] = array(

    // Config
    'config'      => array(
        'dataContainer'    => 'Table',        
        'enableVersioning' => true,
        'sql'              => array(
            'keys' => array(
                'id' => 'primary'
            )
        ),
    ),
    'edit'        => array(
        'buttons_callback' => array(
            array('tl_voucher_gift', 'buttonsCallback')
        )
    ),
    'list'        => array(
        'sorting'         => array(
            'mode'        => 1,            
            'fields'      => array('tstamp'),
            'flag'        => 12,
            'panelLayout' => 'filter;sort,search,limit'
        ),
        'label'             => array(
            'fields' => array('datetime','giftCode','giftCredit','acceptor','invoice','status'),
            'showColumns'             => true,
            'label_callback'          => array('tl_voucher_gift', 'acceptorTitle')
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
                'label' => &$GLOBALS['TL_LANG']['tl_voucher_gift']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),/*
            'copy'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_voucher_gift']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif'
            ),*/
            'delete' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_voucher_gift']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show'   => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_voucher_gift']['show'],
                'href'       => 'act=show',
                'icon'       => 'show.gif',
                'attributes' => 'style="margin-right:3px"'
            ),
        )
    ),
    // Palettes
    'palettes'    => array(
        'default'      => '{voucher_legend},giftCode,giftCredit;{confrim_legend},acceptor,invoice,datetime,status;{note_legend:hide},note'
    ),   
    // Fields
    'fields'      => array(
        'id'             => array(
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp'         => array(
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'giftCode'  => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('disabled'=>true,'mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(20) NOT NULL default ''"
        ),        
        'giftCredit' => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('disabled'=>true,'mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ),    
        'datetime'          => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('disabled'=>true,'mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'invoice'  => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('disabled'=>true,'mandatory' => true, 'maxlength' => 20, 'tl_class' => 'w50'),
            'sql'       => "varchar(20) NULL default ''"
        ),
        'acceptor'          => array(
            'inputType' => 'select',
            'foreignKey'=> 'tl_voucher_acceptor.title',            
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,            
            'eval'      => array('disabled'=>true,'multiple'=>false, 'fieldType'=>'select', 'foreignTable'=>'tl_voucher_acceptor', 'titleField'=>'title', 'tl_class' => 'w50'),
            'relation'  => array('type'=>'belongsTo', 'load'=>'lazy'),
            'sql'       => "varchar(255) NULL default ''"
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
        'status' => array
		(
			'exclude'   => true,
			'inputType' => 'select',
            'reference' => $GLOBALS['TL_LANG']['tl_voucher_gift'],
            'options'   => array('new','sent','confrimed', 'duplicate','error','expired'),
			'eval'      => array('disabled'=>false,'tl_class'=>'w50'),
			'sql'       => "char(20) NOT NULL default ''"
		),
    )
);

/**
 * Class tl_voucher_gift
 */
class tl_voucher_gift extends Backend
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
            $arrButtons['customButton'] = '<button type="submit" name="customButton" id="customButton" class="tl_submit customButton" accesskey="x">' . $GLOBALS['TL_LANG']['tl_voucher_gift']['customButton'] . '</button>';
        }

        return $arrButtons;
    }

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
	public function acceptorTitle($row, $label, DataContainer $dc, $args)
	{

        $objAcceptor = VoucherAcceptorModel::findBy('id',$row['acceptor']);        

		$args[3] = $objAcceptor->title;

		return $args;
	}
}
