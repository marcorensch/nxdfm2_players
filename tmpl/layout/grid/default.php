<?php
/**
 * @package    nxdfm2_players
 *
 * @author     proximate <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;
use NXD\Module\FootballManagerPlayers\Site\Helper\GridHelper;
use NXD\Module\FootballManagerPlayers\Site\Helper\PlayersHelper;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('NXDPlayersGridItemCSS', 'modules/mod_nxdfm2_players/tmpl/layout/grid/assets/css/gridItem.css');

// Build Grid ColumnsString

$gridClassnames = GridHelper::buildGridClassnames($params);

?>

<div class="<?php echo $gridClassnames; ?>" uk-grid>
	<?php foreach ($players as $player):
        $individualData = PlayersHelper::definePlayerData($player, $params);
        ?>
        <div>
            <div class="uk-card uk-card-default nxd-player-grid-card uk-cover-container <?php echo $params->get('custom_card_classnames', '') ?>">
				<?php if ($individualData->image): ?>
					<?php echo LayoutHelper::render('joomla.html.image', ['src' => $individualData->image, 'alt' => $player->firstname . ' ' . $player->lastname, 'class' => 'uk-cover']); ?>
                    <canvas width="200" height="300"></canvas>
				<?php else: ?>
                    <img src="https://via.placeholder.com/200x300"
                         alt="<?php echo $player->firstname . ' ' . $player->lastname; ?>" uk-cover>
				<?php endif; ?>
                <div class="uk-position-bottom">
                    <div class="uk-card-body-container">
                        <div class="uk-card-body <?php echo $params->get('custom_card_content_classnames', '') ?>">
                            <h3 class="uk-card-title <?php echo $params->get('custom_card_title_classnames', '') ?>">
                                <span class="player-number">
                                <?php echo $individualData->number ?? ''; ?>
                                </span>
                            <span class="player-firstname">
                                <?php echo $player->firstname; ?>
                            </span>
                                <span class="player-lastname">
                                <?php echo $player->lastname; ?>
                            </span>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php endforeach; ?>
</div>
