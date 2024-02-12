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

?>

<div class="uk-margin-top player-data">
    <h3 class="person-modal-title person-information-title"><?php echo Text::_('MOD_NXDFM2_PEOPLE_INFORMATION_TITLE');?></h3>
    <table class="uk-table uk-table-striped">
        <tbody>
		<?php foreach ($person->effective->table as $row):
			if($row['value']):
				?>
                <tr>
                    <th class="uk-width-1-3"><?php echo Text::_($row['label']);?></th>
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
</div>