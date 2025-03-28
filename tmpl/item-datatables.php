<?php
/**
 * @package                                     NXD Football Manager 2 People Module (mod_nxdfm2_people)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 * @var $params Joomla\CMS\Parameter\Parameter  The module parameters
 * @var $person stdClass                        The player object
 *
 */

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;

defined('_JEXEC') or die;

$personDataRowTemplate = new FileLayout('item-simple-datatable-row', __DIR__ . '/grid');

?>

<div class="uk-margin-top player-data"
     uk-scrollspy="target: .nxd-animated-row .nxd-animated; cls: uk-animation-slide-left-small; delay: 50; repeat: true">
    <h3 class="nxd-person-modal-title nxd-person-information-title"><?php echo Text::_('MOD_NXDFM2_PEOPLE_INFORMATION_TITLE'); ?></h3>
    <div class="nxd-person-data-table"
         uk-scrollspy="target: > div; cls: uk-animation-slide-bottom-small; delay: 30; repeat: true">
		<?php

		if ($person->active_team->number)
			echo $personDataRowTemplate->render([
				"type"  => "data",
				"label" => "MOD_NXDFM2_PEOPLE_NUMBER_LABEL",
				"value" => $person->active_team->number
			]);
		if ($person->active_team->position && $person->active_team->position->name)
            echo $personDataRowTemplate->render([
                    "type" => "data",
                    "label" => "MOD_NXDFM2_PEOPLE_POSITION_LABEL",
                    "value" => $person->active_team->position->name
            ]);
		if ($person->active_team->since)
            echo $personDataRowTemplate->render([
                    "type" => "data",
                    "label" => "MOD_NXDFM2_PEOPLE_SINCE_LABEL",
                    "value" => HTMLHelper::date($person->active_team->since, $params->get('date_format', 'DATE_FORMAT_LC4'))
            ]);
		if (isset($person->age))
			echo $personDataRowTemplate->render([
				"type" => "data",
				"label" => "MOD_NXDFM2_PEOPLE_AGE_LABEL",
				"value" => $person->age
			]);
		if (isset($person->height))
			echo $personDataRowTemplate->render([
				"type" => "data",
				"label" => "MOD_NXDFM2_PEOPLE_HEIGHT_LABEL",
				"value" => $person->height . "cm"
			]);
		if (isset($person->weight))
			echo $personDataRowTemplate->render([
				"type" => "data",
				"label" => "MOD_NXDFM2_PEOPLE_WEIGHT_LABEL",
				"value" => $person->weight . "kg"
			]);
		if ($person->nation)
            echo $personDataRowTemplate->render([
                    "type" => "data",
                    "label" => "MOD_NXDFM2_PEOPLE_NATION_LABEL",
                    "value" => $person->nation
            ]);
		?>
        <!-- Customfields -->
		<?php include ModuleHelper::getLayoutPath('mod_nxdfm2_people', 'item-cf-table'); ?>
    </div>
</div>