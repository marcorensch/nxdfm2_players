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
	public static function generatePersonsStyles(REgistry $params, array $persons, $moduleId): string
	{
		$css = '';
		foreach ($persons as $person)
		{
			$css .= self::generatePersonStyles($params, $person, $moduleId);
		}
		return $css;
	}
	public static function generatePersonStyles(Registry $params, \stdClass $person, $moduleId): string
	{
		$effectiveTeamDataId = $person->effective->id;
		if (!$effectiveTeamDataId) return '';
		// Find the team in the array of teams where the object key "registration_id" equals to the effectiveTeamDataId
		$team = array_filter($person->teams, fn($team) => $team->registrationId == $effectiveTeamDataId);
		$team = array_shift($team);
		if (!$team) return '';

		$bgColor   = $team->team_color ?: 'transparent';
		$textColor = self::getContrastColor($bgColor);

		$css = "#player-modal-{$moduleId}-{$person->id} .player-name-container {
			background-color: {$bgColor};
			color: {$textColor};
		}";

		return $css;
	}

	public static function getEffectiveTeamLogo(\stdClass $person):string
	{
		$effectiveTeamDataId = $person->effective->id;
		if (!$effectiveTeamDataId) return '';
		$team = array_filter($person->teams, fn($team) => $team->registrationId == $effectiveTeamDataId);
		$team = array_shift($team);
		if (!$team) return '';
		return $team->team_logo ?: '';
	}

	protected function setModuleStates($model, $params, $app): void
	{
		$model->setState('params', $app->getParams());
		$model->setState('filter.teamId', $params->get('team_id', null));
		$model->setState('filter.leagueIds', $params->get('league_ids', null));
		$model->setState('filter.currentTeamOnly', $params->get('only_current_team_positions', '0'));
		$model->setState('filter.activePositionsOnly', $params->get('only_active_positions', '1'));
		$model->setState('params.groupByFieldGroups', $params->get('group_fields', ''));
		$model->setState('sorting.direction', $params->get('sort_direction', 'ASC'));
		$model->setState('sorting.orderBy', $params->get('order_by', 'ordering'));
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
		$data           = new \stdClass();
		$teamId         = $params->get('team_id', null);
		$preparedNumber = null;
		$age            = isset($person->birthdate) ? PeopleHelper::calculateAge($person->birthdate) : null;

		// check if we have a custom setup for this team that is still active (no until date)
		if (isset($person->teams))
		{
			$activeTeams       = array_filter($person->teams, fn($team) => ($team->team_id == $teamId && !$team->until));
			$positionsCombined = implode(', ', array_map(fn($team) => $team->position, $activeTeams));
			$effectiveTeamData = array_shift($activeTeams);
			// A player might be active on multiple positions - merge them
			$effectiveTeamData->positionsCombined = $positionsCombined;

			$data->history = array_filter($person->teams, fn($team) => $team->until);

		}

		$data->id       = $effectiveTeamData->registrationId;
		$data->image    = $effectiveTeamData->image ?: PeopleHelper::definePersonImage($person, $params);
		$data->position = $effectiveTeamData->positionsCombined;

		if ($effectiveTeamData->number !== null)
		{
			$data->number         = $effectiveTeamData->number;
			$preparedNumber       = (int) $effectiveTeamData->number < 10 ? "0" . $effectiveTeamData->number : $effectiveTeamData->number;
			$data->preparedNumber = $preparedNumber;
		}

		$data->table = array();
		if ($preparedNumber) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_NUMBER_LABEL", 'value' => $preparedNumber);
		if ($effectiveTeamData->positionsCombined) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_POSITION_LABEL", 'value' => $effectiveTeamData->positionsCombined);
		if ($effectiveTeamData->since) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_SINCE_LABEL", 'value' => HTMLHelper::date($effectiveTeamData->since, 'Y'));
		if ($age > 0) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_AGE_LABEL", 'value' => $age);
		if (isset($person->country)) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_COUNTRY_LABEL", 'value' => $person->country);
		if (isset($person->height) && $person->height) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_HEIGHT_LABEL", 'value' => $person->height);
		if (isset($person->weight) && $person->weight) $data->table[] = array('label' => "MOD_NXDFM2_PEOPLE_WEIGHT_LABEL", 'value' => $person->weight);

		return $data;
	}


	protected function sortItems(&$items, $params): void
	{
		// Order by Number OR position if selected
		if (in_array($params->get('order_by', 'ordering'), array('number', 'position')))
		{
			$field = $params->get('order_by', 'ordering');
			// order the array of items by the value of the key number
			if ($params->get('sort_direction', 'ASC') === 'ASC')
			{
				usort($items, fn($a, $b) => strnatcmp($a->effective->$field, $b->effective->$field));
			}
			else
			{
				usort($items, fn($a, $b) => strnatcmp($b->effective->$field, $a->effective->$field));
			}
		}
	}

	/**
	 * @throws \Exception
	 */
	private static function calculateAge($birthdate): int
	{
		if (!$birthdate) return 0;
		$birthdate = new \DateTime($birthdate);
		$today     = new \DateTime();
		$age       = $birthdate->diff($today);

		return $age->y;
	}

	private static function getContrastColor($bgColor):string
	{
		$bgColor = strtolower(trim($bgColor));
		// If the background colour is transparent, use the default text colour
		if ($bgColor === 'transparent')
		{
			return 'initial';
		}

		// Handle RGB Colors
		if(str_starts_with("#", $bgColor)){
			[$r, $g, $b] = self::hexToRgb($bgColor);
		}elseif (str_starts_with("hsl", $bgColor)){
			[$r, $g, $b] = self::hslToRgb($bgColor);
		}else{
			// Define rgb(r,g,b) into array [$r,$g,$b]:
			[$r,$g,$b] = self::rgbStringToArray($bgColor);
		}

		// Calculate the brightness
		$brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

		// Decide based on the brightness
		// Use black text for light backgrounds and white text for dark backgrounds
		return ($brightness > 155) ? '#000000' : '#ffffff';
	}

	private static function rgbStringToArray(string $rgbString): array {
		// Remove spaces
		$rgbString = preg_replace('/\s+/', '', $rgbString);

		// Regex for both RGB and RGBA formats
		$rgbaPattern = '/^rgba?\((\d{1,3}),(\d{1,3}),(\d{1,3})(?:,([01]?\.?\d*))?\)$/';

		if (!preg_match($rgbaPattern, $rgbString, $matches)) {
			error_log('Invalid RGB/RGBA color code: ' . $rgbString);
			return [255, 255, 255];
		}


		// Extract and validate for int val
		$rgbValues = [
			(int)$matches[1],
			(int)$matches[2],
			(int)$matches[3]
		];

		// Validate for int range 0-255
		foreach ($rgbValues as $value) {
			if ($value < 0 || $value > 255) {
				error_log('Invalid RGB/RGBA color code: ' . $rgbString);
				return [255,255,255];
			}
		}

		return $rgbValues;
	}


	private static function hexToRgb($color):array
	{
		// Remove '#' character
		$color = ltrim($color, '#');

		// Handle short hex notation (#RGB)
		if (strlen($color) === 3) {
			$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
		}

		// Ensure valid hex code
		if (strlen($color) !== 6 || !ctype_xdigit($color)) {
			// Fallback to default color
			error_log('Invalid hex color code: ' . $color);
			return ['r' => 255, 'g' => 255, 'b' => 255];
		}

		// Convert Hex to RGB
		return [
			'r' => hexdec(substr($color, 0, 2)),
			'g' => hexdec(substr($color, 2, 2)),
			'b' => hexdec(substr($color, 4, 2))
		];
	}

	private static function hslToRgb($hsl):array
	{
		// Extract HSL values
		preg_match('/hsl\((\d+),\s*(\d+)%,\s*(\d+)%\)/i', $hsl, $matches);

		if (count($matches) !== 4) {
			// Fallback for invalid format
			return ['r' => 255, 'g' => 255, 'b' => 255];
		}

		$h = $matches[1] / 360;
		$s = $matches[2] / 100;
		$l = $matches[3] / 100;

		$r = $g = $b = 0;

		if ($s == 0) {
			$r = $g = $b = $l;
		} else {
			$q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
			$p = 2 * $l - $q;

			$r = self::hueToRgb($p, $q, $h + 1/3);
			$g = self::hueToRgb($p, $q, $h);
			$b = self::hueToRgb($p, $q, $h - 1/3);
		}

		return [
			'r' => round($r * 255),
			'g' => round($g * 255),
			'b' => round($b * 255)
		];
	}

	private static function hueToRgb($p, $q, $t):int
	{
		if ($t < 0) $t += 1;
		if ($t > 1) $t -= 1;
		if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
		if ($t < 1/2) return $q;
		if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
		return $p;
	}


}