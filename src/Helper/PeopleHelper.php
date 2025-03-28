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
use NXD\Module\FootballManagerPeople\Site\Model\PersonModel;

class PeopleHelper
{
	public static function generatePeopleStyles(array $persons, $moduleId): string
	{
		$css = '';
		foreach ($persons as $person)
		{
			$css .= self::generatePersonStyles($person, $moduleId);
		}
		return $css;
	}
	public static function generatePersonStyles(PersonModel $person, $moduleId): string
	{
		if(!isset($person->active_team)) return '';

		$bgColor   = $person->active_team->color ?: 'transparent';
		$textColor = self::getContrastColor($bgColor);

		$css = "#person-modal-{$moduleId}-{$person->id} .nxd-person-name-container {
			background-color: {$bgColor};
			color: {$textColor};
		}";

		return $css;
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