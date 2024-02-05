<?php
/**
 * @package                                     NXD Football Manager 2 Players Module (mod_nxdfm2_players)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 * @var $params Joomla\CMS\Parameter\Parameter  The module parameters
 * @var $player stdClass                        The player object
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
<a href="<?php echo '#player-modal-' . $player->id; ?>" uk-toggle class="uk-position-cover nxd-z-index-100"></a>

<!-- This is the modal -->
<div id="<?php echo 'player-modal-' . $player->id; ?>"
     class="nxd-players-module-modal uk-modal-full <?php echo $params->get('modal_container_classnames', ''); ?>"
     uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>

        <div class="uk-grid-collapse <?php echo $gridClassname; ?>" uk-grid>
            <div class="uk-cover-container <?php echo $visibilityClassname; ?>">
	            <?php echo HTMLHelper::image($player->effective->image, 'Player Image', ['uk-cover' => 'true']) ?>
            </div>
            <div class="uk-position-relative" uk-height-viewport>

                <div class="uk-position-relative uk-padding" uk-margin>
					<?php echo HTMLHelper::image($player->effective->image, 'Player Image', ['class' => $imageSmallClassname, 'width' => '200', 'height' => '200']); ?>

                    <div class="player-name-container">
                        <span class="player-name">
                            <span class="player-firstname">
                                <?php echo $player->firstname; ?>
                            </span>
                            <span class="player-lastname">
                                <?php echo $player->lastname; ?>
                            </span>
                        </span>
                        <span class="uk-display-block uk-text-meta uk-text-uppercase uk-text-muted"><?php echo $player->effective->position; ?></span>
                    </div>

                    <div class="player-about">
						<?php echo $player->about; ?>
                    </div>

                    <div class="player-data">
                        <table class="uk-table uk-table-striped">
                            <tbody>
                            <tr>
                                <th>Number</th>
                                <td><?php echo $player->effective->number;?></td>
                            </tr>
                            <tr>
                                <th>Position</th>
                                <td><?php echo $player->effective->position;?></td>
                            </tr>
                            <tr>
                                <th>Since</th>
                                <td><?php echo HTMLHelper::date($player->effective->since, Text::_($params->get('date_format','DATE_FORMAT_LC4')));?></td>
                            </tr>
                            <?php if($player->height): ?>
                            <tr>
                                <th>Height</th>
                                <td><?php echo $player->height;?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($player->weight): ?>
                            <tr>
                                <th>Weight</th>
                                <td><?php echo $player->weight;?></td>
                            </tr>
                            <?php endif; ?>
                            <!-- Customfields -->

                            <?php require ModuleHelper::getLayoutPath('mod_nxdfm2_players', 'customfields_table'); ?>


                            <!-- Customfields -->
                            </tbody>
                        </table>

	                    <?php if(count($player->teams) > 1 || (count($player->teams) && !$player->teams[0]->historyHidden)): ?>
                            <span uk-toggle="target: #<?php echo 'player-'.$player->id.'-history'?>" type="button" class="player-modal-showmore player-history-title"><?php echo Text::_('MOD_NXDFM2_PLAYER_SHOW_HISTORY')?></span>
                            <div id="<?php echo 'player-'.$player->id.'-history'?>" hidden>
                            <table class="uk-table uk-table-striped uk-table-small">
                                <tbody>
		                    <?php foreach($player->teams as $team):
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
