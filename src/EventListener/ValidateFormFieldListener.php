<?php
// src/EventListener/ValidateFormFieldListener.php

namespace App\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Form;
use Contao\Widget;

use Respinar\ContaoVoucherBundle\Model\VoucherAcceptorModel;
use Respinar\ContaoVoucherBundle\Model\VoucherGiftModel;

/**
 * @Hook("validateFormField")
 */
class ValidateFormFieldListener
{
    public function __invoke(Widget $widget, string $formId, array $formData, Form $form): Widget
    {
        if ('giftform' === $formId) {
            // Do your custom validation and add an error if widget does not validate
            if (!$this->validateWidget($widget)) {
                $widget->addError('My custom widget error');
            }
        }

        return $widget;
    }
}