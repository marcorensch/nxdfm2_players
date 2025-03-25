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

<div class="uk-margin-top player-data"
     uk-scrollspy="target: .nxd-animated-row .nxd-animated; cls: uk-animation-slide-left-small; delay: 150; repeat: true">
    <h3 class="nxd-person-modal-title nxd-person-information-title"><?php echo Text::_('MOD_NXDFM2_PEOPLE_INFORMATION_TITLE'); ?></h3>
    <div class="nxd-person-data-table"
         uk-scrollspy="target: > div; cls: uk-animation-slide-bottom-small; delay: 100; repeat: true">
		<?php foreach ($person->effective->table as $row): ?>
			<?php if ($row['value']): ?>
                <div class="nxd-person-data-row uk-width-expand">
                    <div class="nxd-animated-row">
                        <div class="uk-grid-small" uk-grid>
                            <div class="uk-width-1-3">
                                <div class="nxd-animated nxd-person-data-label"><?php echo Text::_($row['label']); ?></div>
                            </div>
                            <div class="uk-width-expand">
                                <div class="nxd-animated nxd-person-data-value"><?php echo $row['value']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
			<?php endif; ?>
		<?php endforeach; ?>
        <!-- Customfields -->

	    <?php include ModuleHelper::getLayoutPath('mod_nxdfm2_people', 'item-cf-table'); ?>

        <!-- Customfields -->
    </div>
</div>