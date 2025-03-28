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
 * @var $team   array                           contains the people to display
 * @var $module /stdClass                       the module instance
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;

// First check if the FootballManager Component is installed
if (!ComponentHelper::getComponent('com_footballmanager', true)->enabled)
{
	echo '<div class="alert alert-error">The Football Manager component is not installed or not enabled.</div>';

	return;
}

if (empty($team)) return;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
if ($params->get('load_uikit', 1))
{
	$wa->getRegistry()->addRegistryFile('media/com_footballmanager/joomla.asset.json');
	$wa->useScript('com_footballmanager.uikitjs');
	$wa->useStyle('com_footballmanager.uikitcss');
}

$wa->registerAndUseStyle('NXDPlayersCSS', 'modules/mod_nxdfm2_people/tmpl/assets/css/module.css');

$wa->registerAndUseStyle('NXDPlayersGridItemCSS', 'modules/mod_nxdfm2_people/tmpl/assets/css/grid.css');
$paddingBottomPercent = (100 / explode(":", $params->get('preview_img_aspect_ratio', '4:3'))[0]) * explode(":", $params->get('preview_img_aspect_ratio', '4:3'))[1];
$wa->addInlineStyle('
    #nxd-people-module-' . $module->id . ' .nxd-people-image-container {
        width: 100%;
        height: 0;
        padding-bottom: ' . $paddingBottomPercent . '%;
    }'
);

if ($params->get('debug', 0)): ?>
    <div class="uk-section uk-section-default uk-border-rounded uk-margin-bottom uk-padding-small">
        <div class="uk-container">
            <div class="uk-h3">NXD FootballManager 2 Debug Container</div>
            <span class="uk-text-meta">Disable Debug option in the Advanced Tab of the Module with ID <?php echo $module->id; ?> to hide this information.</span>
            <ul uk-accordion>
                <li>
                    <a class="uk-accordion-title" href>Parameters</a>
                    <div class="uk-accordion-content">
						<?php
						echo '<pre>' . var_export($params, true) . '</pre>';
						?>
                    </div>
                </li>
                <li>
                    <a class="uk-accordion-title" href>Data</a>
                    <div class="uk-accordion-content">
                        <ul uk-accordion>
							<?php
							foreach ($team as $person):
								?>
                                <li>
                                    <a class="uk-accordion-title"
                                       href><?php echo $person->firstname . " " . $person->lastname; ?></a>
                                    <div class="uk-accordion-content">
										<?php
										echo '<pre>' . var_export($person, true) . '</pre>';
										?>
                                    </div>
                                </li>
							<?php
							endforeach;
							?>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
<?php endif; ?>

<?php
echo '<div id="nxd-people-module-' . $module->id . '" class="nxd-people-module ' . $params->get('moduleclass_container_sfx', '') . '">';
include ModuleHelper::getLayoutPath('mod_nxdfm2_people', $params->get('layout', 'grid') . '/default');
echo '</div>';
