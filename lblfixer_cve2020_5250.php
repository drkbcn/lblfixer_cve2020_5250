<?php
/**
* 2020 Labelgrup
*
* NOTICE OF LICENSE
*
* READ ATTACHED LICENSE.TXT
*
*  @author    Manel Alonso <malonso@labelgrup.com>
*  @copyright 2020 Labelgrup
*  @license   LICENSE.TXT
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Lblfixer_cve2020_5250 extends Module
{
    public function __construct()
    {
        $this->name = 'lblfixer_cve2020_5250';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Labelgrup';
        $this->need_instance = 0;
        $this->displayName = $this->l('Labelgrup.com FIX CVE-2020-5250');
        $this->description = $this->l('Override to FIX CVE-2020-5250 vulnerability.');

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();
        
        // Compatibilidad PS
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install();
    }

    public function uninstall()
    {
        return parent::uninstall();
    }
}
