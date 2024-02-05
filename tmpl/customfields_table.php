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

use NXD\Module\FootballManagerPlayers\Site\Helper\CustomFieldHelper;

defined('_JEXEC') or die;
?>

<?php foreach($player->cfields['groups'] as $cfGroup):?>
	<tr>
		<th><?php echo $cfGroup[0]->group_title; ?></th>
		<td>
			<?php foreach($cfGroup as $cfField): ?>
				<?php CustomFieldHelper::renderField($cfField) ?>
			<?php endforeach; ?>
		</td>
	</tr>
<?php endforeach; ?>
