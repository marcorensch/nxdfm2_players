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


namespace NXD\Module\FootballManagerPlayers\Site\Helper;

defined('_JEXEC') or die;

use NXD\Component\Footballmanager\Site\Model\PlayersModel;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;

class PlayersHelper
{
	public static function definePlayerData(mixed $player, Registry $params): \stdClass
	{
		$teamId = $params->get('team_id', null);
		$playerImage = null;
		$playerNumber = null;
		$playerPosition = null;
		$since = null;

		// Add HistoryHidden Attribute to all teams
		foreach ($player->teams as $team)
		{
			$team->historyHidden = false;
		}

		// check if we have a custom setup for this team that is still active (no until date)
		$customTeam = null;
		foreach ($player->teams as $team)
		{
			if($team->team_id == $teamId && !$team->until)
			{
				$customTeam = $team;
				$team->historyHidden = true;
				break;
			}
		}

		// if we have no custom team setup, use the data for the current team (if any we use the first entry)
		if(!$customTeam)
		{
			foreach ($player->teams as $team)
			{
				if($team->team_id == $teamId)
				{
					$customTeam = $team;
					$team->historyHidden = true;
					break;
				}
			}
		}

		// if we have a custom team setup, use that data
		if($customTeam)
		{
			$registrationId = $customTeam->registrationId;
			$playerImage = $customTeam->image;
			$playerNumber = $customTeam->number;
			$playerPosition = $customTeam->position;
			$since = $customTeam->since;
		}

		$playerImage = $playerImage ?: $player->image;
		$playerImage = $playerImage ?: $params->get('fallback_image', null);


		$data  = new \stdClass();
		$data->id = $registrationId;
		$data->image = $playerImage;
		$data->number = $playerNumber;
		$data->position = $playerPosition;
		$data->since = $since;

		return $data;
	}

	public function getPlayers(Registry $params, SiteApplication $app): array
	{
		if(!ComponentHelper::isEnabled('com_footballmanager', true))
		{
			return array();
		}
		$model = $app->bootComponent('com_footballmanager')->getMVCFactory()->createModel('Players', 'Site', ['ignore_request' => true]);
		$model->setState('params', $app->getParams());

		// Set the module params
		$model->setState('filter.teamId', $params->get('team_id', null));
		$model->setState('filter.currentTeamOnly', $params->get('only_current_team_positions', '0'));
		$model->setState('filter.activePositionsOnly', $params->get('only_active_positions', '1'));
		$model->setState('params.groupByFieldGroups', $params->get('group_fields',''));

		$items = $model->getItems();

		// Build the individual data for each player
		foreach ($items as $player)
		{
			$player->effective = self::definePlayerData($player, $params);
		}

		return $items;
	}
}