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
      
      $objVocucher = VoucherGiftModel::findBy('voucherCode',$submittedData['voucherCode']);
      $objAcceptor = VoucherAcceptorModel::findBy('code',$submittedData['acceptorCode']);

      if ($objVocucher == null)
      {
        if ($objAcceptor == null) {
          $status = 'error';          
        }
        else
        {
          $status = 'confrimed';
          $acceptor = $objAcceptor->id;          
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

      $submittedData['status'] = $status;
      $submittedData['acceptor'] = $acceptor;

      $fields['status'] = $status;
      $fields['acceptor'] = $acceptor;

      //print_r($fields);

    }
}