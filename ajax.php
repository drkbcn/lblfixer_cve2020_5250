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

use Symfony\Component\Finder\Finder;

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/vendor/autload.php');

$token = Tools::getValue('token');
$action = Tools::getValue('action');
$security_token = Configuration::get('LBLCVE_TOKEN');

if ($token == $security_token) {
    // Variables
    $files_pattern = array();
    $message = null;

    // Check for every file to be patched
    $from_where = '/diff';
    $files_type = '*.pattern';

    $finder = new Finder();
    $finder->files()->name($files_type)->in($from_where);

    // Search for pattern files
    if ($finder->hasResults()) {
        foreach ($finder as $file) {
            $files_pattern = $fichero->getRealPath();
        }
    }

    switch('action')
    {
        case 'patch':

        break;
        case 'list_patches':
            $message = json_encode($files_pattern);

        break;
    }
    return $message;

?>