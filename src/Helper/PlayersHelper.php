<?php
namespace NXD\Module\FootballManagerPlayers\Site\Helper;

use NXD\Component\Footballmanager\Site\Model\PlayersModel;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;

class PlayersHelper
{
	public function getPlayers(Registry $params, SiteApplication $app): array
	{
		$model = $app->bootComponent('com_footballmanager')->getMVCFactory()->createModel('Players', 'Site', ['ignore_request' => true]);
		$model->setState('params', $app->getParams());
		$items = $model->getItems();
		return $items;
	}
}