<?php
// src/EventListener/ValidateFormFieldListener.php

namespace Respinar\ContaoVoucherBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Form;
use Contao\Widget;

use Respinar\ContaoVoucherBundle\Model\VoucherGiftModel;

/**
 * @Hook("validateFormField")
 */
class ValidateFormFieldListener
{
    public function __invoke(Widget $widget, string $formId, array $formData, Form $form): Widget
    {
        if ('giftform' === $formId && $widget instanceof \Contao\FormTextField && 'giftCode' === $widget->name) {
            // Do your custom validation and add an error if widget does not validate
            $objGift = VoucherGiftModel::findBy('giftCode',$formDate['giftCode']);

            if (!$objGift) {
                $widget->addError('This code does not exist');
            }
        }

        return $widget;
    }
}