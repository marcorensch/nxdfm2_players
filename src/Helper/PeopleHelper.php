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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

class PeopleHelper
{
	protected function setModuleStates($model, $params, $app): void
	{
		$model->setState('params', $app->getParams());
		$model->setState('filter.teamId', $params->get('team_id', null));
		$model->setState('filter.leagueIds', $params->get('league_ids', null));
		$model->setState('filter.currentTeamOnly', $params->get('only_current_team_positions', '0'));
		$model->setState('filter.activePositionsOnly', $params->get('only_active_positions', '1'));
		$model->setState('params.groupByFieldGroups', $params->get('group_fields',''));
		$model->setState('sorting.direction', $params->get('sort_direction','ASC'));
		$model->setState('sorting.orderBy', $params->get('order_by','ordering'));
	}

	protected static function definePersonImage($person, $params): string
	{
		$image = $person->image;
		$image = $image ?: $params->get('fallback_image', null);
		$image = $image ?: "modules/mod_nxdfm2_people/tmpl/assets/images/400x600.png";

		return $image;
	}

	/**
	 * @throws \Exception
	 */
	public static function definePersonData(mixed $person, Registry $params): \stdClass
	{
		$teamId = $params->get('team_id', null);
		$registrationId = $person->id;
		$personImage = null;
		$playerNumber = null;
		$preparedNumber = null;
		$personPosition = $person->position ?? null;
		$since = null;
		$age = isset($person->birthdate) ? PeopleHelper::calculateAge($person->birthdate) : null;

		// check if we have a custom setup for this team that is still active (no until date)
		if(isset($person->teams))
		{
			// Add HistoryHidden Attribute to all teams
			foreach ($person->teams as $team)
			{
				$team->historyHidden = false;
			}

			$customTeam = null;

			foreach ($person->teams as $team)
			{
				if ($team->team_id == $teamId && !$team->until)
				{
					$customTeam          = $team;
					$team->historyHidden = true;
					break;
				}
			}

			// if we have no custom team setup, use the data for the current team (if any we use the first entry)
			if (!$customTeam)
			{
				foreach ($person->teams as $team)
				{
					if ($team->team_id == $teamId)
					{
						$customTeam          = $team;
						$team->historyHidden = true;
						break;
					}
				}
			}

			// if we have a custom team setup, use that data
			if ($customTeam)
			{
				$registrationId = $customTeam->registrationId;
				$personImage    = $customTeam->image;
				$playerNumber   = $customTeam->number;
				$personPosition = $customTeam->position;
				$since          = $customTeam->since;
			}
		}

		$data  = new \stdClass();
		$data->id = $registrationId;
		$data->image = $personImage ?: PeopleHelper::definePersonImage($person, $params);
		$data->position = $personPosition;
		if($playerNumber !== null)
		{
			$data->number = $playerNumber;
			$preparedNumber = (int) $playerNumber < 10 ? "0" . $playerNumber : $playerNumber;
			$data->preparedNumber = $preparedNumber;
		}

		$data->table = array();
		if($preparedNumber) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_NUMBER_LABEL", 'value' => $preparedNumber);
		if($personPosition) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_POSITION_LABEL", 'value' => $personPosition);
		if($since) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_SINCE_LABEL", 'value' => HTMLHelper::date($since, 'Y'));
		if($age > 0) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_AGE_LABEL", 'value' => $age);
		if(isset($person->country)) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_COUNTRY_LABEL", 'value' => $person->country);
		if(isset($person->height) && $person->height) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_HEIGHT_LABEL", 'value' => $person->height);
		if(isset($person->weight) && $person->weight) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_WEIGHT_LABEL", 'value' => $person->weight);

		return $data;
	}


	protected function sortItems(&$items, $params): void
	{
		// Order by Number OR position if selected
		if(in_array($params->get('order_by','ordering'), array('number','position'))){
			$field = $params->get('order_by','ordering');
			// order the array of items by the value of the key number
			if($params->get('sort_direction','ASC') === 'ASC')
			{
				usort($items, fn($a, $b) => strnatcmp($a->effective->$field, $b->effective->$field));
			}else{
				usort($items, fn($a, $b) => strnatcmp($b->effective->$field, $a->effective->$field));
			}
		}
	}

	/**
	 * @throws \Exception
	 */
	private static function calculateAge($birthdate): int
	{
		if(!$birthdate) return 0;
		$birthdate = new \DateTime($birthdate);
		$today = new \DateTime();
		$age = $birthdate->diff($today);
		return $age->y;
	}
}