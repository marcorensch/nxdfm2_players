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
 * @var $players array                        contains the players
 *
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

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
if ($params->get('load_uikit', 1))
{
	$wa->getRegistry()->addRegistryFile('media/com_footballmanager/joomla.asset.json');
	$wa->useScript('com_footballmanager.uikitjs');
	$wa->useStyle('com_footballmanager.uikitcss');
}

$wa->registerAndUseStyle('NXDPlayersCSS', 'modules/mod_nxdfm2_players/tmpl/assets/css/module.css');

if ($params->get('debug', 0)): ?>
    <div class="uk-section">
        <div class="uk-container">
            <ul uk-accordion>
                <li>
                    <a class="uk-accordion-title" href>Debug</a>
                    <div class="uk-accordion-content">
						<?php
						echo '<pre>' . var_export($players, true) . '</pre>';
						echo '<pre>' . var_export($params, true) . '</pre>';
						?>
                    </div>
                </li>
            </ul>
        </div>
    </div>

<?php endif; ?>
<?php
echo '<div class="nxd-players-module ' . $params->get('moduleclass_container_sfx', '') . '">';
    require ModuleHelper::getLayoutPath('mod_nxdfm2_players', $params->get('layout', 'grid') . '_default');
echo '</div>';
