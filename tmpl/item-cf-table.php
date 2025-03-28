<?php
/**
 * @package                                     NXD Football Manager 2 People Module (mod_nxdfm2_people)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 * @var $params Joomla\Registry\Registry        The module parameters
 * @var $person stdClass                        The person object
 *
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use NXD\Module\FootballManagerPeople\Site\Helper\CustomFieldHelper;

defined('_JEXEC') or die;

$personDataRowTemplate = new FileLayout('item-simple-datatable-row', __DIR__ . '/grid');

?>

<?php
if (isset($person->custom_fields)):
	foreach ($person->custom_fields as $customField):
        if(!$customField->value) continue;
		echo $personDataRowTemplate->render([
			"type" => "data",
			"label" => $customField->title,
			"value" => $customField->value
		]);
    endforeach;
endif;
?>
