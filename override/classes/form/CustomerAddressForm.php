<?php
/**
 * 2007-2018 PrestaShop.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
use Symfony\Component\Translation\TranslatorInterface;

/**
 * StarterTheme TODO: FIXME:
 * In the old days, when updating an address, we actually:
 * - checked if the address was used by an order
 * - if so, just mark it as deleted and create a new one
 * - otherwise, update it like a normal entity
 * I *think* this is not necessary now because the invoicing thing
 * does its own historization. But this should be checked more thoroughly.
 */
class CustomerAddressForm extends CustomerAddressFormCore
{

    public function submit()
    {
        if (!$this->validate()) {
            return false;
        }

        $address = new Address(
            Tools::getValue('id_address'),
            $this->language->id
        );

        foreach ($this->formFields as $formField) {
            $address->{$formField->getName()} = $formField->getValue();
        }

        if (!isset($this->formFields['id_state'])) {
            $address->id_state = 0;
        }

        if (empty($address->alias)) {
            $address->alias = $this->translator->trans('My Address', [], 'Shop.Theme.Checkout');
        }

        Hook::exec('actionSubmitCustomerAddressForm', array('address' => &$address));

        $this->setAddress($address);

        return $this->getPersister()->save(
            $address,
            $this->getValue('token')
        );
    }
}