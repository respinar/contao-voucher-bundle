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

namespace Respinar\ContaoVoucherBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\Input;
use Contao\Date;
use Contao\Form;
use Contao\FrontendUser;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Template;
use Contao\Database;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Respinar\ContaoVoucherBundle\Model\VoucherAcceptorModel;
use Respinar\ContaoVoucherBundle\Model\VoucherGiftModel;
use Respinar\ContaoVoucherBundle\Model\VoucherStaffModel;
use Respinar\ContaoVoucherBundle\Model\VoucherCardModel;


/**
 * Class VoucherValidateModuleController
 *
 * @FrontendModule(VoucherValidateModuleController::TYPE, category="voucher_validate", template="mod_voucher_validate_module")
 */
class VoucherValidateModuleController extends AbstractFrontendModuleController
{
    
    public const TYPE = 'voucher_validate_module';

    /**
     * @var PageModel
     */
    protected $page;

    /**
     * This method extends the parent __invoke method,
     * its usage is usually not necessary
     */
    public function __invoke(Request $request, ModuleModel $model, string $section, array $classes = null, PageModel $page = null): Response
    {
        // Get the page model
        $this->page = $page;

        if ($this->page instanceof PageModel && $this->get('contao.routing.scope_matcher')->isFrontendRequest($request))
        {
            // If TL_MODE === 'FE'
            $this->page->loadDetails();
        }

        return parent::__invoke($request, $model, $section, $classes);
    }

    /**
     * Lazyload some services
     */
    public static function getSubscribedServices(): array
    {
        $services = parent::getSubscribedServices();

        $services['contao.framework'] = ContaoFramework::class;
        $services['database_connection'] = Connection::class;
        $services['contao.routing.scope_matcher'] = ScopeMatcher::class;
        $services['security.helper'] = Security::class;
        $services['translator'] = TranslatorInterface::class;

        return $services;
    }

    /**
     * Generate the module
     */
    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        
        $giftCode = Input::post('giftCode');
        $acceptorCode = Input::post('acceptorCode');
        $invoice = Input::post('invoice');

        $giftObj = VoucherGiftModel::findBy('giftCode',$giftCode);
        

        if (!$giftObj)
        {
            $template->error = $GLOBALS['TL_LANG']['ERR']['invalid-gift-card']; 
        }
        else if ($giftObj->status == "used")
        {
            $template->error = $GLOBALS['TL_LANG']['ERR']['used-gift-card']; 
        }
        else if ($giftObj->expirationDate < time())
        {
            $template->error = $GLOBALS['TL_LANG']['ERR']['expired-gift-card']; 
        }
        else
        {

            $cardObj = VoucherCardModel::findBy('id',$giftObj->pid);

            $acceptorObj = VoucherAcceptorModel::findBy('code',$acceptorCode);

            if (!$acceptorObj) {
                $template->error = $GLOBALS['TL_LANG']['ERR']['invalid-acceptor']; 
            }
            else
            {
                if ($acceptorObj->type != $cardObj->type)
                {
                    $template->error = $GLOBALS['TL_LANG']['ERR']['inconsistent-acceptor'];
                }
                else
                {
                    $staffObj = VoucherStaffModel::findBy('id',$giftObj->staffID);

                    $totalCredit = $giftObj->giftQty * $giftObj->giftCredit;

                    $template->staffName = $staffObj->name;
                    $template->acceptorTitle = $acceptorObj->title;

                    $template->giftTitle = $cardObj->title;
                    $template->giftCode = $giftCode;
                    $template->giftCredit = $giftObj->giftCredit;
                    $template->giftQty = $giftObj->giftQty;

                    $template->expirationDate = Date::parse($this->page->dateFormat,$giftObj->expirationDate);

                    $template->totalCredit = $totalCredit;
                    $template->invoice = $invoice;

                    
                    $balance = ($invoice > $totalCredit) ? $invoice - $totalCredit : 0;
                    
                    $template->balance = $balance;

                    while (true)
                    {
                        $trackingCode = rand(10000000,99999999);
                        $trackingObj = VoucherGiftModel::findBy('trackingCode',$trackingCode);

                        if (!$trackingObj)
                        {                            
                            break;
                        }                
                    }

                    $template->trackingCode = $trackingCode;

                    $arrSet['acceptorID'] = $acceptorObj->id;
                    $arrSet['invoice'] = $invoice;
                    $arrSet['balance'] = $balance;
                    $arrSet['trackingCode'] = $trackingCode;
                    $arrSet['datetime'] = time();
                    $arrSet['status'] = "used";

                    $db   = Database::getInstance();
                    $stmt = $db->prepare("UPDATE tl_voucher_gift %s WHERE id=?")->set($arrSet)->execute($giftObj->id);
        
                }
            }
        }

        return $template->getResponse();
    }
}
