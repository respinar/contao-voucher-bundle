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
        'ptable'           => 'tl_voucher_card',
        'enableVersioning' => true,
        'sql'              => array(
            'keys' => array(
                'id' => 'primary'
            )
        ),
        'onsubmit_callback'       => array
        (
            array('tl_voucher_gift', 'adjustGift'),
            array('tl_voucher_gift', 'sendSMS')
        )
    ),
    'edit'        => array(
        'buttons_callback' => array(
            array('tl_voucher_gift', 'sendSMSButton')
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
            'fields' => array('tstamp','giftCode','giftQty','staffID', 'expirationDate','status'),
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
        'default'      => '{staff_legend},staffID,occasion;{gift_legend},giftCode,expirationDate,giftCredit,giftQty;{confrim_legend},acceptorID,invoice,datetime,trackingCode,status;{note_legend:hide},note'
    ),   
    // Fields
    'fields'      => array(
        'id'             => array(
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
		(
			'foreignKey' => 'tl_voucher_card.title',
            'sql'        => "int(10) unsigned NOT NULL default '0'"
		),
        'tstamp'         => array(
            'flag' => 6,
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
        'giftCode'  => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
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
        'expirationDate' => array
		(
			'exclude'   => true,			
			'sorting'   => true,
			'flag'      => 6,
			'inputType' => 'text',
			'eval'      => array('rgxp'=>'date', 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),			
			'sql'       => "int(10) unsigned NULL"
		),
        'datetime'          => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,            
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('disabled'=>true,'mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'invoice'  => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,            
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('disabled'=>true,'mandatory' => false, 'maxlength' => 20, 'tl_class' => 'w50'),
            'sql'       => "int(10) unsigned NULL"
        ),
        'balance'  => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,            
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('disabled'=>true,'mandatory' => false, 'maxlength' => 20, 'tl_class' => 'w50'),
            'sql'       => "int(10) unsigned NULL"
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
            'sql'       => "int(10) unsigned NULL"
        ),  
        'trackingCode'  => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,            
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('disabled'=>true,'mandatory' => false, 'maxlength' => 20, 'tl_class' => 'w50'),
            'sql'       => "int(10) unsigned NULL"
        ),     
        'status' => array
		(
            'inputType' => 'select',
            'default'   => 'new',
            'filter'    => 'true',
			'exclude'   => true,
			'inputType' => 'select',
            'reference' => $GLOBALS['TL_LANG']['tl_voucher_gift'],
            'options'   => array('new','sent','uesd','expired'),
			'eval'      => array('readonly'=>true,'tl_class'=>'w50'),
			'sql'       => "char(10) NOT NULL default ''"
		),    
        'occasion' => array(
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,            
            'sorting'   => true,
            'eval'      => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'note'  => array(
            'inputType' => 'textarea',
            'exclude'   => true,
            'search'    => true,            
            'sorting'   => true,
            'eval'      => array('rte' => 'tinyMCE', 'tl_class' => 'clr'),
            'sql'       => 'text NULL'
        )        
    )
);

/**
 * Class tl_voucher_gift
 * 
 */
class tl_voucher_gift extends Backend
{

       /**
     * onsubmit callback
     * Run the bundle maker.
     *
     * @throws Exception
     */
    public function sendSMS(DataContainer $dc): void
    {
        //$dc->activeRecord->status = "send";

        if ('' !== Input::get('id') && '' === Input::post('sendSMS') && 'tl_voucher_gift' === Input::post('FORM_SUBMIT') && 'auto' !== Input::post('SUBMIT_TYPE')) 
        {
            $arrSet['status'] = "sent";
            if($arrSet) {
                $this->Database->prepare("UPDATE tl_voucher_gift %s WHERE id=?")->set($arrSet)->execute($dc->id);
            }
        }

    }


    /**
     * @param $arrButtons
     * @param  DC_Table $dc
     * @return mixed
     */
    public function sendSMSButton($arrButtons, DC_Table $dc)
    {
        if (Input::get('act') === 'edit')
        {
            $arrButtons['sendSMS'] = '<button type="submit" name="sendSMS" id="sendSMS" class="tl_submit sendSMS">' . $GLOBALS['TL_LANG']['tl_voucher_gift']['sendSMS'] . '</button>';
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

        $cardObj = VoucherCardModel::findBy('id',$dc->activeRecord->pid);

        if (!($dc->activeRecord->giftCredit)) {
            $arrSet['giftCredit'] = $cardObj->credit;
        }        

        if (!($dc->activeRecord->giftQty))
        {
            $staffObj = VoucherStaffModel::findBy('id', $dc->activeRecord->staffID);

            $arrSet['giftQty'] = ($cardObj->single) ? 1 : $staffObj->familyMembers;
        }
                    
        if (!($dc->activeRecord->expirationDate))
        {
            $arrSet['expirationDate'] = $cardObj->expiration * 86400 + time();
        }        

        if (!($dc->activeRecord->giftCode)) {
            
            while (true)
            {
                $code = rand(100000,999999);
                $giftObj = VoucherGiftModel::findBy('giftCode',$code);

                if (!$giftObj)
                {
                    $arrSet['giftCode'] = $code;
                    break;
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

        //$objAcceptor = VoucherAcceptorModel::findBy('id',$row['acceptorID']);        
        //$objCard = VoucherCardModel::findBy('id',$row['pid']);
        $objStaff = VoucherStaffModel::findBy('id',$row['staffID']);
        
        $args[3] = $objStaff->name;
        //$args[4] = $objAcceptor->title;

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
