<?php

/**
 * @package                                     NXD Football Manager 2 People Module (mod_nxdfm2_people)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 * @var \Joomla\Registry\Registry $params The module parameters
 *
 */


namespace NXD\Module\FootballManagerPeople\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Date\Date;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Registry\Registry;
use NXD\Module\FootballManagerPeople\Site\Model\TeamDataModel;


class PersonModel
{
	public int $id = 0;
	public string $firstname = '';
	public string $lastname = '';
	public string $image = '';
	public string $about;
	public string|null $nation;

	/**
	 * @var TeamDataModel[]|null $teams Array of TeamData items or null
	 * @since 2.0.0
	 */
	public ?array $teams = null;
	public TeamDataModel|null $active_team = null;
	/**
	 * @var DataFieldModel[]|null $data_table Array of DataFields or null
	 * @since 2.0.0
	 */
	public ?array $data_table = null;

	public array $custom_fields = [];

	protected string $type = 'person';

	/**
	 * @throws \Exception
	 *
	 * @since 2.0.0
	 */
	public function __construct(\stdClass $personData, Registry $params)
	{
		$this->id            = $personData->id;
		$this->firstname     = trim($personData->firstname);
		$this->lastname      = trim($personData->lastname);
		$this->teams         = self::setTeams($personData->teams, $params->get('only_active_positions', 1));
		$this->active_team   = $this->setActiveTeam($params->get('team_id', 0));
		$this->image         = self::definePersonImage($personData->image);
		$this->about         = $personData->about;
		$this->nation        = $personData->nation;
		$this->custom_fields = $this->getCustomFields();
	}

	/**
	 *
	 * Returns the count of years as int between the $birthdate (string) and now
	 *
	 * @param $birthdate string         Valid Date as string value
	 *
	 * @return int|null
	 *
	 * @since 2.0.0
	 */
	protected function calculateAge(?string $birthdate): int|null
	{
		if (!$birthdate) return null;
		$birthdate = new Date($birthdate);
		$today     = new Date();
		$age       = $birthdate->diff($today);

		return $age->y;
	}

	/**
	 * @param   string  $teams            JSON Encoded string that contains the team data for this person
	 * @param   bool    $only_active_pos  Module parameter that defines the status for positions with enddates
	 *
	 * @return array
	 *
	 * @since version
	 */
	private static function setTeams(string|array $teams, bool $only_active_pos): array
	{
		if (!$teams) return array();
		if (is_string($teams))
		{
			$teams = json_decode($teams, true);
		}

		if (!$teams) return array();

		return array_map(fn($team) => new TeamDataModel($team, $only_active_pos), $teams);
	}

	private function setActiveTeam(int $current_team_id = 0): TeamDataModel|null
	{
		if (!$this->teams || !$current_team_id) return null;
		$team        = array_filter($this->teams, fn($team) => $team->id == $current_team_id);
		$currentTeam = array_shift($team);
		if (!$currentTeam) return null;

		return $currentTeam;
	}

	private function definePersonImage($mainImageUrl): string
	{
		// Set the general image as src
		$image = $mainImageUrl;

		if ($this->active_team && $this->active_team->image)
		{
			$image = $this->active_team->image;
		}

		return trim($image);
	}

	protected function getCustomFields(): array
	{
		$groupFields = false; // can be added as feature if needed
		$type = $this->type;

		// Get the associated fields for the currently used type (player, coach, cheerleader)
		$fields = FieldsHelper::getFields('com_footballmanager.'.$type, $this, true);

		// Group the fields by field group if requested.
		if ($groupFields)
		{
			return $this->groupFields($fields, $groupFields);
		}

		return $fields;

	}

	/**
	 * Group fields by field group if requested. returns the manipulated array containing the key groups of field groups containing the fields and the key fields containing the fields that are not in a group
	 *
	 * @param $fields           array       of fields
	 * @param $groupFields      array       of field group ids that should be grouped
	 *
	 * @return array
	 * @since 1.0.0
	 */
	protected function groupFields($fields, $groupFields): array
	{
		$groups = array();
		$index  = 0;
		foreach ($fields as $field)
		{
			if (in_array($field->group_id, $groupFields))
			{
				$groups[$field->group_id][] = $field;
				// remove field from fields array
				unset($fields[$index]);
			}
			$index++;
		}
		$customFields = array(
			'fields' => $fields,
			'groups' => $groups
		);

		return $customFields;
	}
}