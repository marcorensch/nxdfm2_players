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

use NXD\Module\FootballManagerPlayers\Site\Helper\GridHelper;

// Build Grid ColumnsString

$gridClassnames = GridHelper::buildGridClassnames($params);

?>

<div class="uk-grid uk-grid-small <?php echo $gridClassnames;?>" uk-grid>
	<?php foreach ($players as $player): ?>
		<div>
			<div class="uk-card uk-card-default nxd-player-grid-card">
				<h3 class="uk-card-title"><?php echo $player->firstname . ' ' . $player->lastname; ?></h3>
				<p><?php echo $player->nickname; ?></p>
				<p><?php echo $player->height; ?></p>
				<p><?php echo $player->weight; ?></p>
				<p><?php echo $player->sponsors; ?></p>
				<p><?php echo $player->teams; ?></p>
			</div>
		</div>
	<?php endforeach; ?>
</div>
