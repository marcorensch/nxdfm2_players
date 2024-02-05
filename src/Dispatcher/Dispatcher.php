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


namespace NXD\Module\FootballManagerPlayers\Site\Dispatcher;

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Joomla\Registry\Registry;

class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
	use HelperFactoryAwareTrait;

	protected function getLayoutData()
	{
//		$settings = new Registry($this->module->params);
//		dump($settings);

		$data = parent::getLayoutData();
		$data['players'] = $this->getHelperFactory()->getHelper('PlayersHelper')->getPlayers($data['params'], $this->getApplication());
		return $data;
	}
}