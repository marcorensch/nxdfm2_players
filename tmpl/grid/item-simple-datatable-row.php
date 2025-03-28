<?php
/**
 * @package                                     NXD Football Manager 2 People Module (mod_nxdfm2_people)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 * @var $displayData    array       The DisplayData for this Layout (see Joomla FileLayout Class)
 *
 */

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

$type = $displayData['type'];

?>


<div class="nxd-person-<?php echo $type;?>-row uk-width-expand">
	<div class="nxd-animated-row">
		<div class="uk-grid-small" uk-grid>
			<div class="uk-width-1-3">
				<div class="nxd-animated nxd-person-data-label"><?php echo Text::_($displayData['label']); ?></div>
			</div>
			<div class="uk-width-expand">
				<div class="nxd-animated nxd-person-data-value"><?php echo $displayData['value']; ?></div>
			</div>
		</div>
	</div>
</div>