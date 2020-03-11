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
 * StarterTheme TODO: B2B fields, Genders, CSRF.
 */
class CustomerForm extends CustomerFormCore
{
    public function fillFromCustomer(Customer $customer)
    {
        $params = get_object_vars($customer);
        $params['birthday'] = $customer->birthday === '0000-00-00' ? null : Tools::displayDate($customer->birthday);

        return $this->fillWith($params);
    }

    /**
     * @return \Customer
     */
    public function getCustomer()
    {
        $customer = new Customer($this->context->customer->id);

        foreach ($this->formFields as $field) {
            $customerField = $field->getName();
            if (property_exists($customer, $customerField)) {
                $customer->$customerField = $field->getValue();
            }
        }

        return $customer;
    }

    public function validate()
    {
        $emailField = $this->getField('email');
        $id_customer = Customer::customerExists($emailField->getValue(), true, true);
        $customer = $this->getCustomer();
        if ($id_customer && $id_customer != $customer->id) {
            $emailField->addError($this->translator->trans(
                'The email is already used, please choose another one or sign in', array(), 'Shop.Notifications.Error'
            ));
        }

        // birthday is from input type text..., so we need to convert to a valid date
        $birthdayField = $this->getField('birthday');
        if (!empty($birthdayField)) {
            $birthdayValue = $birthdayField->getValue();
            if (!empty($birthdayValue)) {
                $dateBuilt = DateTime::createFromFormat($this->context->language->date_format_lite, $birthdayValue);
                if (!empty($dateBuilt)) {
                    $birthdayField->setValue($dateBuilt->format('Y-m-d'));
                }
            }
        }

        $this->validateFieldsLengths();
        $this->validateByModules();

        return parent::validate();
    }
}
