<?php
// src/EventListener/ProcessFormDataListener.php
namespace Respinar\ContaoVoucherBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Form;

use Respinar\ContaoVoucherBundle\Model\VoucherAcceptorModel;
use Respinar\ContaoVoucherBundle\Model\VoucherGiftModel;

/**
 * @Hook("processFormData")
 */
class ProcessFormDataListener
{
    public function __invoke(
        array $submittedData, 
        array $formData, 
        ?array $files, 
        array $labels, 
        Form $form
    ): void
    {
        
        $objGift = VoucherGiftModel::findBy('giftCode',$submittedData['giftCode']);

        if ($objGift) {

            $objAcceptor = VoucherAcceptorModel::findBy('code',$submittedData['acceptorCode']);

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

            $status = 'Fatal!';

            // کد گیفت یافت نشد

        }

        $formData['acceptorID'] = $acceptorID;
        $formData['status'] = $status;

        $formData['acceptorID'] = 1;//$acceptorID;
        $formData['status'] = "Hamid";

        $submittedData['acceptorID'] = 2;
        $submittedData['status'] = "mana";

    }
}

