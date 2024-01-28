<?php
namespace NXD\Module\FootballManagerPlayers\Site\Helper;

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

		// check if we have a custom setup for this team that is still active (no until date)
		$customTeam = null;
		foreach ($player->teams as $team)
		{
			if($team->team_id == $teamId && !$team->until)
			{
				$customTeam = $team;
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
					break;
				}
			}
		}

		// if we have a custom team setup, use that data
		if($customTeam)
		{
			$playerImage = $customTeam->image;
			$playerNumber = $customTeam->number;
		}

		$playerImage = $playerImage ?: $player->image;
		$playerImage = $playerImage ?: $params->get('fallback_image', null);


		$data  = new \stdClass();
		$data->image = $playerImage;
		$data->number = $playerNumber;

		return $data;
	}

	public function getPlayers(Registry $params, SiteApplication $app): array
	{
		$model = $app->bootComponent('com_footballmanager')->getMVCFactory()->createModel('Players', 'Site', ['ignore_request' => true]);
		$model->setState('params', $app->getParams());

		// Set the module params
		$model->setState('filter.teamId', $params->get('team_id', null));
		$model->setState('filter.currentTeamOnly', $params->get('only_current_team_positions', '0'));
		$model->setState('filter.activePositionsOnly', $params->get('only_active_positions', '1'));

		$items = $model->getItems();
		return $items;
	}
}