<?php
/**
 * @package                                     NXD Football Manager 2 People Module (mod_nxdfm2_people)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 * @var $params Joomla\Registry\Registry        The module parameters
 * @var $person stdClass                        The person object
 *
 */

use Joomla\CMS\Language\Text;
use NXD\Module\FootballManagerPeople\Site\Helper\CustomFieldHelper;

defined('_JEXEC') or die;
?>

<?php
if (isset($person->cfields['groups'])):
	foreach ($person->cfields['groups'] as $cfGroup):?>
        <div class="nxd-person-data-row uk-width-expand">
            <div class="nxd-animated-row">
                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-1-3">
                        <div class="nxd-animated nxd-person-data-label"><?php echo Text::_($cfGroup[0]->group_title); ?></div>
                    </div>
                    <div class="uk-width-expand">
                        <div class="nxd-animated nxd-person-data-value">
							<?php foreach ($cfGroup as $cfField): ?>
								<?php CustomFieldHelper::renderField($cfField) ?>
							<?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php endforeach;
endif;
?>
