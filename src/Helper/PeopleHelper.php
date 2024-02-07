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

class PeopleHelper
{
	protected function setModuleStates($model, $params, $app): void
	{
		$model->setState('params', $app->getParams());
		$model->setState('filter.teamId', $params->get('team_id', null));
		$model->setState('filter.currentTeamOnly', $params->get('only_current_team_positions', '0'));
		$model->setState('filter.activePositionsOnly', $params->get('only_active_positions', '1'));
		$model->setState('params.groupByFieldGroups', $params->get('group_fields',''));
	}

	protected static function definePersonImage($person, $params): string
	{
		$image = $person->image;
		$image = $image ?: $params->get('fallback_image', null);
		$image = $image ?: "modules/mod_nxdfm2_people/tmpl/assets/images/400x600.png";

		return $image;
	}
}