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

use Contao\BackendUser;
use Contao\Config;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\DataContainer;
use Contao\Date;
use Contao\Image;
use Contao\StringUtil;
use Contao\System;
use Contao\Versions;

use Respinar\ContaoVoucherBundle\Model\VoucherAcceptorModel;
use Respinar\ContaoVoucherBundle\Model\VoucherStaffModel;
use Respinar\ContaoVoucherBundle\Model\VoucherCardModel;
use Respinar\ContaoVoucherBundle\Model\VoucherGiftModel;


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
        'onsubmit_callback'       => array
        (
            array('tl_voucher_gift', 'adjustGift')
        )
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
            'fields' => array('cardID','giftCode','giftQty','staffID', 'acceptorID','invoice','datetime','status'),
            'showColumns'             => true,
            'label_callback'          => array('tl_voucher_gift', 'titles')
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
        'default'      => '{staff_legend},staffID,occasion;{voucher_legend},cardID,giftQty,giftCode,giftCredit;{confrim_legend},acceptorID,invoice,datetime,status;{note_legend:hide},note'
    ),   
    // Fields
    'fields'      => array(
        'id'             => array(
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp'         => array(
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'staffID'          => array(
            'inputType' => 'select',
            'foreignKey'=> 'tl_voucher_staff.name',            
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,            
            'eval'      => array('chosen'=>true, 'mandatory'=>true, 'includeBlankOption'=>true,'multiple'=>false, 'submitOnChange'=>true, 'fieldType'=>'select', 'foreignTable'=>'tl_voucher_staff', 'titleField'=>'name', 'tl_class' => 'w50'),
            'relation'  => array('type'=>'belongsTo', 'load'=>'lazy'),
            'sql'       => "varchar(255) NULL default ''"
        ),
        'cardID'          => array(
            'inputType' => 'select',
            'foreignKey'=> 'tl_voucher_card.title',            
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,            
            'eval'      => array('chosen'=>true, 'mandatory'=>true, 'includeBlankOption'=>true,'multiple'=>false, 'submitOnChange'=>true, 'fieldType'=>'select', 'foreignTable'=>'tl_voucher_card', 'titleField'=>'title', 'tl_class' => 'w50'),
            'relation'  => array('type'=>'belongsTo', 'load'=>'lazy'),
            'sql'       => "varchar(255) NULL default ''"
        ),
        'giftQty'  => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('disabled'=>false,'mandatory' => false, 'maxlength' => 2, 'tl_class' => 'w50'),
            'sql'       => "int(2) unsigned NOT NULL default 0"
        ),   
        'giftType'  => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('disabled'=>false,'mandatory' => false, 'maxlength' => 20, 'tl_class' => 'w50'),
            'sql'       => "varchar(20) NOT NULL default ''"
        ),
        'giftCode'  => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'save_callback' => array('tl_voucher_gift','serialCodeCallback'),
            'eval'      => array('disabled'=>true,'mandatory' => false, 'unique'=>true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(20) NOT NULL default ''"
        ),        
        'giftCredit' => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('disabled'=>true,'mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ),    
        'datetime'          => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('disabled'=>true,'mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'invoice'  => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('disabled'=>true,'mandatory' => false, 'maxlength' => 20, 'tl_class' => 'w50'),
            'sql'       => "varchar(20) NULL default ''"
        ),
        'acceptorID' => array(
            'inputType' => 'select',
            'foreignKey'=> 'tl_voucher_acceptor.title',            
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,            
            'eval'      => array('disabled'=>true,'multiple'=>false, 'includeBlankOption'=>true, 'fieldType'=>'select', 'foreignTable'=>'tl_voucher_acceptor', 'titleField'=>'title', 'tl_class' => 'w50'),
            'relation'  => array('type'=>'belongsTo', 'load'=>'lazy'),
            'sql'       => "varchar(255) NULL default ''"
        ),           
        'occasion' => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
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
			'eval'      => array('disabled'=>true,'tl_class'=>'w50'),
			'sql'       => "char(20) NOT NULL default ''"
		),
    )
);

/**
 * Class tl_voucher_gift
 * 
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
     * Check status, if it is "answered", present a mail form
     * 
     * @param DataContainer $dc
     */
    public function adjustGift(DataContainer $dc)
    {
        // Return if there is no active record (override all)
		if (!$dc->activeRecord)
		{
			return;
		}

        if ($dc->activeRecord->staffID)
        {
            $staffObj = VoucherStaffModel::findBy('id', $dc->activeRecord->staffID);
            $arrSet['giftQty'] =  $staffObj->family_member_qty;
        }

        if ($dc->activeRecord->cardID) {
            
            $cardObj = VoucherCardModel::findBy('id',$dc->activeRecord->cardID);
                    
            $arrSet['giftCredit'] = $cardObj->credit;
            $arrSet['giftType'] = $cardObj->type;

            if ( !$dc->activeRecord->giftCode ) {

                $success = false;
                
                while (!$success)
                {
                    $code = rand(100000,999999);
                    $giftObj = VoucherGiftModel::findBy('giftCode',$code);

                    if (!$giftObj)
                    {
                        $arrSet['giftCode'] = $code;
                        $success = true;
                    }                
                }
            }

        }
        
        if($arrSet) {
            $this->Database->prepare("UPDATE tl_voucher_gift %s WHERE id=?")->set($arrSet)->execute($dc->id);
        }
        
        
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
	public function titles($row, $label, DataContainer $dc, $args)
	{

        $objAcceptor = VoucherAcceptorModel::findBy('id',$row['acceptorID']);        
        $objCard = VoucherCardModel::findBy('id',$row['cardID']);
        $objStaff = VoucherStaffModel::findBy('id',$row['staffID']);

        $args[0] = $objCard->title;
        $args[3] = $objStaff->name;
        $args[4] = $objAcceptor->title;

		return $args;
	}

    /**
     * @param $serialCode
     * @return string
     */
    public function serialCodeCallback($serialCode)
    {
        
        $serialCode = rand(100000,999999);

        return $serialCode;
    }

}
