<?php
// src/EventListener/PrepareFormDataListener.php
namespace Respinar\ContaoVoucherBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Form;

use Respinar\ContaoVoucherBundle\Model\VoucherAcceptorModel;
use Respinar\ContaoVoucherBundle\Model\VoucherGiftModel;


/**
 * @Hook("prepareFormData")
 */
class PrepareFormDataListener
{
    public function __invoke(array &$submittedData, array $labels, array $fields, Form $form): void
    {
      
      $objGift = VoucherGiftModel::findBy('giftCode',$data['giftCode']);

      if ($objGift) {

        $objAcceptor = VoucherAcceptorModel::findBy('code',$data['acceptorCode']);

        $acceptorID = $objAcceptor->code;

        if ($objAcceptor)
        {

          $status = 'confrimed';

        }
        else
        {

          $status = 'error';

        }
      }
      else
      {

        // کد گیفت یافت نشد

      }

      $submittedData['acceptorID'] = $acceptorID;
      $submittedData['status'] = $status;

      

      $fields['status'] = $status;
      $fields['acceptor'] = $acceptorID;

      //print_r($fields);

    }
}