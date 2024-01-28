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


use Joomla\CMS\Factory;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
if ($params->get('load_uikit', 1))
{
	$wa->getRegistry()->addRegistryFile('media/com_footballmanager/joomla.asset.json');
	$wa->useScript('com_footballmanager.uikitjs');
	$wa->useStyle('com_footballmanager.uikitcss');
}

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
// set the path to the layout file
$template   = $params->get('layout', 'grid');
$layoutPath = JPATH_BASE . '/modules/mod_nxdfm2_players/tmpl/layout/' . $template . '/default.php';

// Check if the file exists & include it
if (file_exists($layoutPath)) include $layoutPath;
