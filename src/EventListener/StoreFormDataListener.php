<?php
// src/EventListener/StoreFormDataListener.php
namespace Respinar\ContaoVoucherBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Form;
//use Contao\FrontendUser;
//use Doctrine\DBAL\Connection;
//use Symfony\Component\Security\Core\Security;

use Respinar\ContaoVoucherBundle\Model\AcceptorModel;
use Respinar\ContaoVoucherBundle\Model\VoucherModel;

/**
 * @Hook("storeFormData")
 */
class StoreFormDataListener
{
    

    public function __invoke(array $data, Form $form): array
    {
      
      $objVocucher = VoucherModel::findBy('voucherCode',$data['voucherCode']);
      $objAcceptor = AcceptorModel::findBy('code',$data['acceptorCode']);

      if ($objVocucher == null)
      {
        if ($objAcceptor == null) {
          $status = 'error';          
        }
        else
        {
          $acceptor = $objAcceptor->id;
          $status = 'confrimed';
        }                
      }
      else
      {
        if ($objVocucher->status != 'error')
        {
          $status = 'duplicate';
          $acceptor = $objAcceptor->id;
        }
        else
        {
          if ($objAcceptor == null) {
            $status = 'error';
            $acceptor = $objAcceptor->id;        
          }
          else
          {
            $acceptor = $objAcceptor->id;
            $status = 'confrimed';
          }
        }
      }

      $data['acceptor'] = $acceptor;
      $date['status'] = $status;

      return $data;
    }

}
