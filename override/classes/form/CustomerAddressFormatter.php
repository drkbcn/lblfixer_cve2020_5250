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

class CustomerAddressFormatter extends CustomerAddressFormatterCore
{
    public function getFormat()
    {
        $fields = AddressFormat::getOrderedAddressFields(
            $this->country->id,
            true,
            true
        );
        $required = array_flip(AddressFormat::getFieldsRequired());

        $format = [
            'back' => (new FormField())
                ->setName('back')
                ->setType('hidden'),
            'token' => (new FormField())
                ->setName('token')
                ->setType('hidden'),
            'alias' => (new FormField())
                ->setName('alias')
                ->setLabel(
                    $this->getFieldLabel('alias')
                ),
        ];

        foreach ($fields as $field) {
            $formField = new FormField();
            $formField->setName($field);

            $fieldParts = explode(':', $field, 2);

            if (count($fieldParts) === 1) {
                if ($field === 'postcode') {
                    if ($this->country->need_zip_code) {
                        $formField->setRequired(true);
                    }
                } elseif ($field === 'phone') {
                    $formField->setType('tel');
                }
            } elseif (count($fieldParts) === 2) {
                list($entity, $entityField) = $fieldParts;

                // Fields specified using the Entity:field
                // notation are actually references to other
                // entities, so they should be displayed as a select
                $formField->setType('select');

                // Also, what we really want is the id of the linked entity
                $formField->setName('id_' . strtolower($entity));

                if ($entity === 'Country') {
                    $formField->setType('countrySelect');
                    $formField->setValue($this->country->id);
                    foreach ($this->availableCountries as $country) {
                        $formField->addAvailableValue(
                            $country['id_country'],
                            $country[$entityField]
                        );
                    }
                } elseif ($entity === 'State') {
                    if ($this->country->contains_states) {
                        $states = State::getStatesByIdCountry($this->country->id, true);
                        foreach ($states as $state) {
                            $formField->addAvailableValue(
                                $state['id_state'],
                                $state[$entityField]
                            );
                        }
                        $formField->setRequired(true);
                    }
                }
            }

            $formField->setLabel($this->getFieldLabel($field));
            if (!$formField->isRequired()) {
                // Only trust the $required array for fields
                // that are not marked as required.
                // $required doesn't have all the info, and fields
                // may be required for other reasons than what
                // AddressFormat::getFieldsRequired() says.
                $formField->setRequired(
                    array_key_exists($field, $required)
                );
            }

            $format[$formField->getName()] = $formField;
        }

        return $this->addConstraints(
                $this->addMaxLength(
                    $format
                )
        );
    }
}