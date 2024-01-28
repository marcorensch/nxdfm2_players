<?php
namespace NXD\Module\FootballManagerPlayers\Site\Dispatcher;

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