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

use NXD\Module\FootballManagerPeople\Site\Helper\CustomFieldHelper;

defined('_JEXEC') or die;
?>

<?php
if(isset($person->cfields['groups'])):
foreach($person->cfields['groups'] as $cfGroup):?>
	<tr>
		<th><?php echo $cfGroup[0]->group_title; ?></th>
		<td>
			<?php foreach($cfGroup as $cfField): ?>
				<?php CustomFieldHelper::renderField($cfField) ?>
			<?php endforeach; ?>
		</td>
	</tr>
<?php endforeach;
endif;
?>
