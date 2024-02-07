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

defined('_JEXEC') or die;

$breakPoint          = $params->get('modal_breakpoint', 'm');
$gridClassname       = 'uk-child-width-1-2@' . $breakPoint;
$visibilityClassname = 'uk-visible@' . $breakPoint;
$imageSmallClassname = 'uk-display-block uk-margin-auto uk-width-3-4@s uk-hidden@' . $breakPoint;

?>
<a href="<?php echo '#player-modal-' . $person->id; ?>" uk-toggle class="uk-position-cover nxd-z-index-100"></a>

<!-- This is the modal -->
<div id="<?php echo 'player-modal-' . $person->id; ?>"
     class="nxd-people-module-modal uk-modal-full <?php echo $params->get('modal_container_classnames', ''); ?>"
     uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>

        <div class="uk-grid-collapse <?php echo $gridClassname; ?>" uk-grid>
            <div class="uk-cover-container <?php echo $visibilityClassname; ?>">
	            <?php echo HTMLHelper::image($person->effective->image, 'Player Image', ['uk-cover' => 'true']) ?>
            </div>
            <div class="uk-position-relative" uk-height-viewport>

                <div class="uk-position-relative uk-padding">
					<?php echo HTMLHelper::image($person->effective->image, 'Player Image', ['class' => $imageSmallClassname, 'width' => '200', 'height' => '200']); ?>

                    <div class="player-name-container">
                        <span class="player-name">
                            <span class="player-firstname">
                                <?php echo $person->firstname; ?>
                            </span>
                            <span class="player-lastname">
                                <?php echo $person->lastname; ?>
                            </span>
                        </span>
                        <span class="uk-display-block uk-text-meta uk-text-uppercase uk-text-muted"><?php echo $person->effective->position; ?></span>
                    </div>

	                <?php if( $person->about): ?>
                    <div class="uk-margin-top player-about">
                        <h3 class="person-about-title"><?php echo Text::_("MOD_NXDFM2_PEOPLE_ABOUT_TITLE");?></h3>
						<?php echo $person->about; ?>
                    </div>
                    <?php endif;?>

                    <div class="uk-margin-large-top player-data">
                        <table class="uk-table uk-table-striped">
                            <tbody>
                            <?php foreach ($person->effective->table as $row):
                                if($row['value']):
                            ?>
                            <tr>
                                <th><?php echo Text::_($row['label']);?></th>
                                <td><?php echo $row['value'];?></td>
                            </tr>
                            <?php
                                endif;
                            endforeach;
                            ?>
                            <!-- Customfields -->

                            <?php include ModuleHelper::getLayoutPath('mod_nxdfm2_people', 'item-cf-table'); ?>

                            <!-- Customfields -->
                            </tbody>
                        </table>

	                    <?php if((isset($person->teams) && count($person->teams) > 1) || ((isset($person->teams) && count($person->teams)) && !$person->teams[0]->historyHidden)): ?>
                            <span uk-toggle="target: #<?php echo 'player-'.$person->id.'-history'?>" type="button" class="player-modal-showmore player-history-title"><?php echo Text::_('MOD_NXDFM2_PLAYER_SHOW_HISTORY')?></span>
                            <div id="<?php echo 'player-'.$person->id.'-history'?>" hidden>
                            <table class="uk-table uk-table-striped uk-table-small">
                                <tbody>
		                    <?php foreach($person->teams as $team):
			                    if($team->historyHidden) continue;
			                    ?>
                                <tr>
                                    <th>Team</th>
                                    <td class="uk-text-bold history-team-title"><?php echo $team->title;?></td>
                                </tr>
                                <tr>
                                    <th>Number</th>
                                    <td class="history-team-number"><?php echo $team->number;?></td>
                                </tr>
                                <tr>
                                    <th>Position</th>
                                    <td class="history-team-position"><?php echo $team->position;?></td>
                                </tr>
                                <tr>
                                    <th>Since / Until</th>
                                    <td class="history-team-since-until"><?php
					                    if($team->since){
						                    echo HTMLHelper::date($team->since, Text::_($params->get('date_format','DATE_FORMAT_LC4')));
					                    }
					                    if($team->until)
					                    {
						                    echo ' - ' . HTMLHelper::date($team->until, Text::_($params->get('date_format', 'DATE_FORMAT_LC4')));
					                    }
					                    ?></td>
                                </tr>
		                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            </div>
	                    <?php endif; ?>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
