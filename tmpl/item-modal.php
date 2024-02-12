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

$hasHistory = (isset($person->teams) && count($person->teams) > 1) || ((isset($person->teams) && count($person->teams)) && !$person->teams[0]->historyHidden);

?>
<a href="<?php echo '#player-modal-' .  $module->id . '-' . $person->id; ?>" uk-toggle class="uk-position-cover nxd-z-index-100"></a>

<!-- This is the modal -->
<div id="<?php echo 'player-modal-' . $module->id . '-' . $person->id; ?>"
     class="nxd-people-module-modal uk-modal-container uk-flex-top <?php echo $params->get('modal_container_classnames', ''); ?>"
     uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical">
        <button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>

        <div class="uk-grid-collapse <?php echo $gridClassname; ?>" uk-grid>
            <div class="uk-cover-container people-modal-image-container <?php echo $visibilityClassname; ?>">
	            <?php echo HTMLHelper::image($person->effective->image, 'Player Image', ['class' => 'nxd-modal-player-image-large']) ?>
            </div>
            <div class="uk-position-relative">

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

                    <div class="nxd-player-information-container">
                    <!-- Tabs -->
                    <ul class="uk-subnav uk-subnav-pill nxd-player-subnav" uk-switcher>
	                    <?php if( $person->about): ?>
                        <li><a href="#"><?php echo Text::_("MOD_NXDFM2_PEOPLE_ABOUT_TITLE");?></a></li>
	                    <?php endif;?>
                        <li><a href="#"><?php echo Text::_("MOD_NXDFM2_PEOPLE_INFORMATION_TITLE");?></a></li>
                        <?php if($hasHistory): ?>
                        <li><a href="#"><?php echo Text::_("MOD_NXDFM2_PEOPLE_HISTORY_TITLE");?></a></li>
                        <?php endif; ?>
	                    <?php if(isset($person->sponsors) && $person->sponsors): ?>
                        <li><a href="#"><?php echo Text::_("MOD_NXDFM2_PEOPLE_SPONSORS_TITLE");?></a></li>
                        <?php endif; ?>
                    </ul>

                    <!-- Switcher Content -->
                    <ul class="uk-switcher nxd-switcher-content">
	                    <?php if( $person->about): ?>
                            <li>
                                <div class="uk-margin-top player-about">
                                    <h3 class="person-modal-title person-about-title"><?php echo Text::_("MOD_NXDFM2_PEOPLE_ABOUT_TITLE");?></h3>
				                    <?php echo $person->about; ?>
                                </div>
                            </li>
	                    <?php endif;?>
                        <li>
	                        <?php include ModuleHelper::getLayoutPath('mod_nxdfm2_people', 'item-datatables'); ?>
                        </li>
                        <?php if($hasHistory): ?>
                        <li>
                            <?php include ModuleHelper::getLayoutPath('mod_nxdfm2_people', 'item-history'); ?>
                        </li>
                        <?php endif; ?>
	                    <?php if(isset($person->sponsors) && $person->sponsors): ?>
                        <li>
	                        <?php include ModuleHelper::getLayoutPath('mod_nxdfm2_people', 'item-sponsors'); ?>
                        </li>
	                    <?php endif; ?>
                    </ul>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
