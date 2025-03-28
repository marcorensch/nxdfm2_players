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

use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\QueryInterface;
use Joomla\Registry\Registry;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

defined('_JEXEC') or die;

class TeamMembersModel extends BaseDatabaseModel
{
	private static function generateSingularContext(string $context): string
	{
		return match ($context)
		{
			'players' => 'player',
			'coaches' => 'coach',
			'cheerleaders' => 'cheerleader',
			default => 'coach',
		};
	}

	public function loadTeamMembersFromDb(Registry $params): array
	{
		$context             = $params->get('context', 'coaches'); // players || coaches || cheerleaders
		$singularContext     = self::generateSingularContext($context);
		$mainTable           = "#__footballmanager_" . $context;
		$context_teams_table = "#__footballmanager_{$context}_teams";

		$db    = Factory::getContainer()->get(DatabaseInterface::class);
		$query = $db->getQuery(true);

		$teamId              = $this->getState('filter.teamId', null);
		$leagueIds           = $this->getState('filter.leagueIds', null);
		$onlyCurrentTeam     = $this->getState('filter.currentTeamOnly', '0');
		$onlyActivePositions = $this->getState('filter.activePositionsOnly', 0);
		$sortingDirection    = $this->getState('sorting.direction', 'ASC');
		$orderBy             = $this->getState('sorting.orderBy', 'ordering');

		$keys = array('id', 'firstname', 'lastname', 'image', 'about', 'country_id', 'ordering');
		if (in_array($context, array('players', 'cheerleaders')))
		{
			$keys[] = 'height';
			$keys[] = 'weight';
			$keys[] = 'birthday';
			if($context == 'players'){
				$keys[] = 'sponsors';
			}
		}
		$columns = array_map(fn($key) => "p.{$key}", $keys);

		$query->select($db->quoteName(
			$columns,
			$keys))
			->from($db->quoteName($mainTable, 'p'));

		// Join Country
		$query->select('c.title as nation')
			->join('LEFT', $db->quoteName('#__footballmanager_countries', 'c') . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('p.country_id'));

		// Create a subquery for the team(s) DATA (only used for display)
		if ($context !== "cheerleaders")
		{
			$teamCols = '
				"team_name", t.title, 
				"team_id", t.id, 
				"team_color", t.color, 
				"team_secondary_color", t.secondary_color, 
				"team_logo", t.logo, 
				"team_logo_inverted", t.inverted_logo';
			$combineCols = '
				"position_id", ct.position_id, 
				"image", ct.image, 
				"since", ct.since, 
				"until", ct.until, 
				"ordering", ct.ordering
			';
			if($context == 'players'){
				$combineCols .= ', "number", ct.player_number';
			}
			$positionCols = '
				"position_name", pos.title, 
				"position_description", pos.description, 
				"position_link", pos.learnmore_link
			';
			$teamsSubquery        = $db->getQuery(true);
			$subQuerySelectString = 'JSON_ARRAYAGG(
			JSON_OBJECT(
				'.$teamCols.',
				'.$combineCols.',
				'.$positionCols.'
			)) as teams';
			$teamsSubquery->select($subQuerySelectString)
				->from($db->quoteName($context_teams_table, 'ct'))
				->join(
					'LEFT',
					$db->quoteName('#__footballmanager_teams', 't') . ' ON ' . $db->quoteName('t.id') . ' = ' . $db->quoteName('ct.team_id')
				)
				->join(
					'LEFT',
					$db->quoteName('#__footballmanager_positions', 'pos') . ' ON ' . $db->quoteName('pos.id') . ' = ' . $db->quoteName('ct.position_id')
				)
				->where($db->quoteName("ct.{$singularContext}_id") . ' = ' . $db->quoteName('p.id'));

			// Filter: Current Team only
			if ($onlyCurrentTeam && $teamId)
			{
				$teamsSubquery->where('(' . $db->quoteName('ct.team_id') . ' = ' . $db->quote($teamId) . ')');
			}

			if ($leagueIds)
			{
				$teamsSubquery->where('(' . $db->quoteName('ct.league_id') . ' IN (' . implode(',', $leagueIds) . '))');
			}

			// Display Filter for ACTIVE Positions only
			if ($onlyActivePositions)
			{
				$teamsSubquery->where('(' . $db->quoteName('ct.since') . ' IS NULL OR ' . $db->quoteName('ct.since') . ' <= NOW())');
				$teamsSubquery->where('(' . $db->quoteName('ct.until') . ' IS NULL OR ' . $db->quoteName('ct.until') . ' >= NOW())');
			}


			$query->select('(' . $teamsSubquery . ') as teams');


			// <<< END OF SUBQUERY for team(s) DATA

			// Create a subquery for the Team & League Filter

			$subQueryFilterTeam = $db->getQuery(true);
			$subQueryFilterTeam->select('GROUP_CONCAT(' . $db->quoteName('ctf.team_id') . ' ORDER BY ' . $db->quoteName('ctf.ordering') . ' SEPARATOR ",")')
				->from($db->quoteName($context_teams_table, 'ctf'))
				->where($db->quoteName("ctf.{$singularContext}_id") . ' = ' . $db->quoteName('p.id'));

			// Filter: League IDs
			if ($leagueIds)
			{
				$subQueryFilterTeam->where('(' . $db->quoteName('ctf.league_id') . ' IN (' . implode(',', $leagueIds) . '))');
			}

			// Filter for Current Team only
			if ($onlyCurrentTeam)
			{
				$subQueryFilterTeam->where('(' . $db->quoteName('ctf.team_id') . ' = ' . $db->quote($teamId) . ')');
				// Filter for League IDs
				if ($leagueIds)
				{
					$subQueryFilterTeam->where('(' . $db->quoteName('ctf.league_id') . ' IN (' . implode(',', $leagueIds) . '))');
				}
			}

			// filter for active Only
			if (true)
			{
				$subQueryFilterTeam->where('(' . $db->quoteName('ctf.since') . ' IS NULL OR ' . $db->quoteName('ctf.since') . ' <= NOW())');
				$subQueryFilterTeam->where('(' . $db->quoteName('ctf.until') . ' IS NULL OR ' . $db->quoteName('ctf.until') . ' >= NOW())');
			}

			// Modify the main query to include the nested query in the WHERE clause
			$query->where('FIND_IN_SET (' . $db->quote($teamId) . ', (' . $subQueryFilterTeam . '))');

		}
		else
		{
			// Nur bei Cheerleaders: Direkte Verbindung zum Team über das team_id-Feld
			$query->select([
				't.title AS team_name',
				't.id AS team_id',
				't.color AS team_color',
				't.secondary_color AS team_secondary_color',
				't.logo AS team_logo',
				't.inverted_logo AS team_logo_inverted'
			])
				->join(
					'LEFT',
					$db->quoteName('#__footballmanager_teams', 't') . ' ON ' . $db->quoteName('t.id') . ' = ' . $db->quoteName('p.team_id')
				);

			$query->where($db->quoteName('p.team_id') . ' = ' . $db->quote($teamId));

		}

		// Only Published Items
		$query->where($db->quoteName('p.published') . ' = ' . $db->quote('1'));

		// Order by Rules (Number & Position will be handled differently inside the module helper)
		if (!in_array($orderBy, array('number', 'position')))
		{
			$query->order('p.' . $orderBy . ' ' . $sortingDirection);
		}

		$db->setQuery($query);
		$people = $db->loadObjectList();

		return $people;

	}

	protected function sortItems(&$people, $params): void
	{
		// Order by Number OR position if selected
		if (in_array($params->get('order_by', 'ordering'), array('firstname','lastname')))
		{
			$field = $params->get('order_by', 'ordering');
			// order the array of items by the value of the key number
			if ($params->get('sort_direction', 'ASC') === 'ASC')
			{
				usort($people, fn($a, $b) => strnatcmp($a->$field, $b->$field));
			}
			else
			{
				usort($people, fn($a, $b) => strnatcmp($b->$field, $a->$field));
			}
		}
		if(in_array($params->get('order_by', 'ordering'), array('number'))){
			if ($params->get('sort_direction', 'ASC') === 'ASC')
			{
				usort($people, fn($a, $b) => strnatcmp($a->active_team->number, $b->active_team->number));
			}
			else
			{
				usort($people, fn($a, $b) => strnatcmp($b->active_team->number, $a->active_team->number));
			}
		}
		if(in_array($params->get('order_by', 'ordering'), array('position'))){
			if ($params->get('sort_direction', 'ASC') === 'ASC')
			{
				usort($people, fn($a, $b) => strnatcmp($a->active_team->position->name, $b->active_team->position->name));
			}
			else
			{
				usort($people, fn($a, $b) => strnatcmp($b->active_team->position->name, $a->active_team->position->name));
			}
		}
	}

	public function setModuleStates(Registry $params): void
	{
		$this->setState('filter.teamId', $params->get('team_id', null));
		$this->setState('filter.leagueIds', $params->get('league_ids', null));
		$this->setState('filter.currentTeamOnly', $params->get('only_current_team_positions', '0'));
		$this->setState('filter.activePositionsOnly', $params->get('only_active_positions', '1'));
		$this->setState('params.groupByFieldGroups', $params->get('group_fields', ''));
		$this->setState('sorting.direction', $params->get('sort_direction', 'ASC'));
		$this->setState('sorting.orderBy', $params->get('order_by', 'ordering'));
	}
}