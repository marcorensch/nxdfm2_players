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

$simpleDataTableRowLayout = new FileLayout('item-simple-datatable-row', __DIR__ . '/grid');

?>

<div id="<?php echo 'player-' . $person->id . '-history' ?>" class="uk-margin-top"
     uk-scrollspy="target: .nxd-animated-row .nxd-animated; cls: uk-animation-slide-left-small; delay: 50; repeat: true">
    <h3 class="person-modal-title person-history-title"><?php echo Text::_('MOD_NXDFM2_PEOPLE_HISTORY_TITLE'); ?></h3>

    <div class="nxd-person-history-table"
         uk-scrollspy="target: > div > div; cls: uk-animation-slide-bottom-small; delay: 30; repeat: true">
		<?php foreach ($person->teams as $team):
			if ($team->hidden) continue;
			?>
            <div class="uk-margin-top uk-border-rounded uk-overflow-hidden">
				<?php echo $simpleDataTableRowLayout->render(["type" => "history", "label" => "MOD_NXDFM2_PEOPLE_TEAM_LABEL", "value" => $team->name]); ?>
				<?php if ($team->league) echo $simpleDataTableRowLayout->render(["type" => "history", "label" => "MOD_NXDFM2_PEOPLE_LEAGUE_LABEL", "value" => $team->league]); ?>
				<?php if ($team->number) echo $simpleDataTableRowLayout->render(["type" => "history", "label" => "MOD_NXDFM2_PEOPLE_NUMBER_LABEL", "value" => $team->number]); ?>
				<?php if ($team->position && $team->position->name) echo $simpleDataTableRowLayout->render(["type" => "history", "label" => "MOD_NXDFM2_PEOPLE_POSITION_LABEL", "value" => $team->position->name]); ?>
				<?php if ($team->until || $team->since): ?>
                    <div class="nxd-person-history-row uk-width-expand">
                        <div class="nxd-animated-row">
                            <div class="uk-grid-small" uk-grid>
                                <div class="uk-width-1-3">
                                    <div class="nxd-animated nxd-person-data-label"><?php echo Text::_("MOD_NXDFM2_PEOPLE_SINCE_UNTIL_LABEL"); ?></div>
                                </div>
                                <div class="uk-width-expand">
                                    <div class="nxd-animated nxd-person-data-value">
										<?php
										if ($team->since)
										{
											if (!$team->until) echo '<span>' . Text::_("MOD_NXDFM2_PEOPLE_SINCE_UNTIL_LABEL_SINCE") . '&nbsp;</span>';
											echo HTMLHelper::date($team->since, Text::_($params->get('date_format', 'DATE_FORMAT_LC4')));
										}
										if ($team->since && $team->until)
										{
											echo ' - ';
										}
										if ($team->until)
										{
											if (!$team->since) echo '<span>' . Text::_("MOD_NXDFM2_PEOPLE_SINCE_UNTIL_LABEL_UNTIL") . '&nbsp;</span>';
											echo HTMLHelper::date($team->until, Text::_($params->get('date_format', 'DATE_FORMAT_LC4')));
										}
										?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>
            </div>
		<?php endforeach; ?>
    </div>
</div>