<?php
// src/EventListener/StoreFormDataListener.php
namespace Respinar\ContaoVoucherBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Form;
//use Contao\FrontendUser;
//use Doctrine\DBAL\Connection;
//use Symfony\Component\Security\Core\Security;

use Respinar\ContaoVoucherBundle\Model\VoucherAcceptorModel;
use Respinar\ContaoVoucherBundle\Model\VoucherGiftModel;

/**
 * @Hook("storeFormData")
 */
class StoreFormDataListener
{
    

    public function __invoke(array $data, Form $form): array
    {
      
      $objGift = VoucherGiftModel::findBy('giftCode',$data['giftCode']);

      if ($objGift) {

        $objAcceptor = VoucherAcceptorModel::findBy('code',$data['acceptorCode']);

        $acceptorID = $objAcceptor->id;

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
        $status = "notfound";

      }

      $data['status'] = $status;
      $data['acceptorID'] = $acceptorID;

      return $data;
     
    }

}
