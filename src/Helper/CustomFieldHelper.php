<?php
/**
 * @package                                     NXD Football Manager 2 People Module (mod_nxdfm2_people)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 */

namespace NXD\Module\FootballManagerPeople\Site\Helper;

defined('_JEXEC') or die;

class CustomFieldHelper
{

	public static function renderField($field)
	{
		if($soMeLink = self::checkForSocialMedia($field))
		{
			return $soMeLink;
		}

		return $field->value;

	}

	private static function checkForSocialMedia($field): false|string
	{
		if(is_string($field->rawvalue) && str_starts_with($field->rawvalue, 'http'))
		{
			// Check if the field rawvalue contains an url that contains partly the social media types defined in the array
			preg_match('/(facebook|instagram|twitter)/i', $field->rawvalue, $matches);

			// remove empty matches
			$matches = array_filter($matches);

			// if we have a match, we have a social media field
			if ($matches)
			{
				return CustomFieldHelper::buildSocialLink($field->rawvalue, $matches[0]);
			}
		}

		return false;
	}

	protected static function buildSocialLink($fieldValue, $socialMediaType): string
	{

		$socialMediaType           = strtolower($socialMediaType);
		$supportedSocialMediaTypes = ['facebook', 'instagram', 'twitter'];
		$iconType = in_array($socialMediaType, $supportedSocialMediaTypes) ? $socialMediaType : 'link';

		$fieldValue = '<a class="social-media-link" href="' . $fieldValue . '" target="_blank"><i class="fab fa-' . $iconType . '-square"></i></a>';

		return $fieldValue;
	}

}