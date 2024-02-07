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
 *
 */


namespace NXD\Module\FootballManagerPeople\Site\Helper;


use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\Registry\Registry;

defined('_JEXEC') or die;

class CheerleadersHelper extends PeopleHelper implements PeopleInterface
{

	public function getPeople(Registry $params, SiteApplication $app): array
	{
		if(!ComponentHelper::isEnabled('com_footballmanager', true))
		{
			return array();
		}
		$model = $app->bootComponent('com_footballmanager')->getMVCFactory()->createModel('Cheerleaders', 'Site', ['ignore_request' => true]);

		// Set the module params
		$this->setModuleStates($model, $params, $app);

		$items = $model->getItems();

		// Build the individual data for each player
		foreach ($items as $person)
		{
			$person->effective = self::definePersonData($person, $params);
		}

		$this->sortItems($items, $params);

		return $items;

	}
}