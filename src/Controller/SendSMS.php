<?php

declare(strict_types=1);

/*
 * This file is part of Contao Bundle Creator Bundle.
 *
 * (c) Marko Cupic 2021 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/contao-bundle-creator-bundle
 */

namespace Respinar\ContaoVoucherBundle\Controller;

use SoapClient;
use Respinar\ContaoVoucherBundle\Model\VoucherGatewayModel;

/**
 * Class BundleMaker.
 */
class SendSMS
{
    public function __construct(int $id)
    {

        $gateWayObj = VoucherGatewayModel::findBy('id',$id);
        
        $this->sms_gateway    =  $gateWayObj->gateway;
        $this->sms_fromNumber =  $gateWayObj->fromNumber;
        $this->sms_username   =  $gateWayObj->username;
        $this->sms_password   =  $gateWayObj->password;
    }

    public function __invoke(string $toNumber, string $message)
    {

        ini_set("soap.wsdl_cache_enabled", "0");

        $sms_client = new SoapClient($this->sms_gateway, array('encoding'=>'UTF-8'));

        try {
            $parameters['userName'] = $this->sms_username;
            $parameters['password'] = $this->sms_password;
            $parameters['fromNumber'] = $this->sms_fromNumber;
            $parameters['toNumbers'] = array($toNumber);
            $parameters['messageContent'] = $message;
            $parameters['isFlash'] = false;
            $recId = array();
            $status = array();
            $parameters['recId'] = &$recId ;
            $parameters['status'] = &$status ;

            $status = $sms_client->SendSMS($parameters)->SendSMSResult;            
        }

        catch (Exception $e) 
        {
            $status = $e->getMessage();
        }

        return $status;
    }
}