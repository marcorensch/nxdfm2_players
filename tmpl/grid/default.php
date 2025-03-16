<?php
/**
 * @package                                     NXD Football Manager 2 People Module (mod_nxdfm2_people)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 * @var $params \Joomla\CMS\HTML\Registry       The module parameters
 * @var $people stdClass                        The persons array
 * @var $module stdClass                        The module instance
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Layout\LayoutHelper;
use NXD\Module\FootballManagerPeople\Site\Helper\GridHelper;
use NXD\Module\FootballManagerPeople\Site\Helper\PeopleHelper;

$playersCss = PeopleHelper::generatePersonsStyles($params, $people, $module->id);

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('NXDPlayersGridItemCSS', 'modules/mod_nxdfm2_people/tmpl/assets/css/grid.css');
if($playersCss){
	$wa->addInlineStyle($playersCss);
}


// Build Grid ColumnsString

$gridClassnames = GridHelper::buildGridClassnames($params);

?>

<div class="<?php echo $gridClassnames; ?>" uk-grid uk-height-match="target: >div >.uk-card > .uk-card-body">
	<?php foreach ($people as $person):
		?>
        <div data-player-number="<?php echo $person->effective->number ?? 0; ?>"
             data-player-firstname="<?php echo strtolower($person->firstname) ?>"
             data-player-lastname="<?php echo strtolower($person->lastname) ?>">
            <div class="uk-card nxd-player-grid-card uk-position-relative <?php echo $params->get('custom_card_classnames', '') ?>">
                <div>
                    <?php if($params->get('overview_bg','')):?>
                    <div class="uk-position-cover uk-cover-container">
	                    <?php echo LayoutHelper::render('joomla.html.image', ['src' => $params->get('overview_bg',''), 'alt' => "", 'class' => 'nxd-people-bg-image', "uk-cover" => "true"]); ?>
                    </div>
                    <?php endif; ?>
                    <div class="nxd-people-image-container uk-cover-container">
		                <?php if ($person->effective->image): ?>
			                <?php echo LayoutHelper::render('joomla.html.image', ['src' => $person->effective->image, 'alt' => $person->firstname . ' ' . $person->lastname, 'class' => 'nxd-people-image', "uk-cover" => "true"]); ?>
		                <?php endif; ?>
                    </div>
                </div>
                <div class="uk-card-body <?php echo $params->get('custom_card_content_classnames', '') ?>">
					<?php if ($params->get('position_in_overview', '') === "top"): ?>
                        <div class="uk-width-1-1 person-position">
							<?php echo $person->effective->position ?? ''; ?>
                        </div>
					<?php endif; ?>
					<?php if (isset($person->effective->preparedNumber)): ?>
                        <div class="uk-width-1-1 player-number">
							<?php echo "#" . $person->effective->preparedNumber ?? ''; ?>
                        </div>
					<?php endif; ?>
                    <h3 class="uk-card-title <?php echo $params->get('custom_card_title_classnames', '') ?>">
                        <div class="player-name">
                            <div class="player-firstname">
								<?php echo $person->firstname; ?>
                            </div>
                            <div class="player-lastname">
								<?php echo $person->lastname; ?>
                            </div>
                        </div>
                    </h3>
					<?php if ($params->get('position_in_overview', '') === "bottom"): ?>
                        <div class="uk-width-1-1 person-position">
							<?php echo $person->effective->position ?? ''; ?>
                        </div>
					<?php endif; ?>
                </div>

				<?php if ($params->get('show_modal', 1)):
					include ModuleHelper::getLayoutPath('mod_nxdfm2_people', 'item-modal');
				endif; ?>

            </div>
        </div>
	<?php endforeach; ?>
</div>
