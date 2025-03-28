<?php
/**
 * @package                                     NXD Football Manager 2 People Module (mod_nxdfm2_people)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 * @var $params \Joomla\Registry\Registry       The module parameters
 *
 */


namespace NXD\Module\FootballManagerPeople\Site\Model;

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

class PlayersModel extends TeamMembersModel
{
	/**
	 * @throws \Exception
	 * @since 2.0.0
	 */
	public function getTeamMembers(Registry $params):array
	{
		$peopleData = $this->loadTeamMembersFromDb($params);
		foreach ($peopleData as &$person){
			$person = new PlayerModel($person, $params);
		}

		// Set the Sorting based on the Settings
		$this->sortItems($peopleData, $params);

		return $peopleData;
	}
}