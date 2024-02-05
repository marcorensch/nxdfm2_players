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

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Layout\LayoutHelper;
use NXD\Module\FootballManagerPlayers\Site\Helper\GridHelper;
use NXD\Module\FootballManagerPlayers\Site\Helper\PlayersHelper;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('NXDPlayersGridItemCSS', 'modules/mod_nxdfm2_players/tmpl/assets/css/grid.css');

// Build Grid ColumnsString

$gridClassnames = GridHelper::buildGridClassnames($params);

echo LayoutHelper::render('test');

?>

<div class="<?php echo $gridClassnames; ?>" uk-grid>
	<?php foreach ($players as $player):
		?>
        <div data-player-number="<?php echo $player->effective->number ?? 0; ?>" data-player-firstname="<?php echo strtolower($player->firstname)?>" data-player-lastname="<?php echo strtolower($player->lastname)?>">
            <div class="uk-card uk-card-default nxd-player-grid-card uk-cover-container uk-position-relative <?php echo $params->get('custom_card_classnames', '') ?>">
				<?php if ($player->effective->image): ?>
					<?php echo LayoutHelper::render('joomla.html.image', ['src' => $player->effective->image, 'alt' => $player->firstname . ' ' . $player->lastname, 'class' => 'uk-cover']); ?>
                    <canvas width="200" height="300"></canvas>
				<?php else: ?>
                    <img src="https://via.placeholder.com/200x300"
                         alt="<?php echo $player->firstname . ' ' . $player->lastname; ?>" uk-cover>
				<?php endif; ?>
                <div class="uk-position-bottom">
                    <div class="uk-card-body-container">
                        <div class="uk-card-body <?php echo $params->get('custom_card_content_classnames', '') ?>">
                            <h3 class="uk-grid-small uk-flex uk-flex-middle uk-card-title <?php echo $params->get('custom_card_title_classnames', '') ?>"
                                uk-grid>
                                <div class="uk-width-auto player-number">
									<?php echo $player->effective->number ?? ''; ?>
                                </div>
                                <div class="uk-width-expand">
                                    <div class="player-name">
                                        <div class="player-firstname">
											<?php echo $player->firstname; ?>
                                        </div>
                                        <div class="player-lastname">
											<?php echo $player->lastname; ?>
                                        </div>
                                    </div>
                                </div>
                            </h3>
                        </div>
                    </div>
                </div>
	            <?php if($params->get('show_modal', 1)):
		            require ModuleHelper::getLayoutPath('mod_nxdfm2_players', $params->get('layout', 'grid') . '_modal');
	            endif; ?>
            </div>
        </div>
	<?php endforeach; ?>
</div>
