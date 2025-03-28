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

class CheerleadersModel extends TeamMembersModel
{
	public function getTeamMembers(Registry $params):array
	{
		$peopleData = $this->loadTeamMembersFromDb($params);
		foreach ($peopleData as &$person){
			$person->teams = array(0 => array(
				"team_id" => $person->team_id,
				"team_name" => $person->team_name,
				"team_logo" => $person->team_logo,
				"team_color" => $person->team_color,
				"team_secondary_color" => $person->team_secondary_color,
				"team_logo_inverted" => $person->team_logo_inverted,
			));
			$person = new CheerleaderModel($person, $params);
		}

		// Set the Sorting based on the Settings
		$this->sortItems($peopleData, $params);

		return $peopleData;
	}
}