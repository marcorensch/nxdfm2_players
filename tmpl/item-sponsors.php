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

<div class="player-sponsors-container uk-margin-top">
    <h3 class="person-modal-title person-sponsors-title"><?php echo Text::_('MOD_NXDFM2_PEOPLE_SPONSORS_TITLE');?></h3>
    <div class="sponsors" uk-margin>
		<?php foreach ($person->sponsors as $sponsor): ?>
            <div class="uk-margin-top">
				<?php include ModuleHelper::getLayoutPath('mod_nxdfm2_people', 'item-sponsor'); ?>
            </div>
		<?php endforeach; ?>
    </div>
</div>