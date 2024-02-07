<?php
/**
 * @package                                     NXD Football Manager 2 People Module (mod_nxdfm2_people)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 * @var $settings Joomla\CMS\Parameter\Parameter  The module parameters
 * @var $player stdClass                        The player object
 *
 */


namespace NXD\Module\FootballManagerPeople\Site\Dispatcher;

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
		$settings = new Registry($this->module->params);

		$data = parent::getLayoutData();
		$helperName = ucfirst($settings->get('context', 'players')) . 'Helper';
		$data['people'] = $this->getHelperFactory()->getHelper($helperName)->getPeople($data['params'], $this->getApplication());
		return $data;
	}
}