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

use DateTime;
use Joomla\CMS\Date\Date;

defined('_JEXEC') or die;

class TeamDataModel
{
	public int $id = 0;
	public string $name;
	public string $logo;
	public string|null $logo_inverted = null;
	public string $league;
	public string $color;
	public PositionModel|null $position = null;
	public string $image;
	public string $until;
	public string $since;

	public int|null $number = null;

	public bool $hidden = false;

	public function __construct(array $teamData, bool $only_active_pos)
	{
		$this->id            = $teamData['team_id'];
		$this->name          = trim($teamData['team_name']);
		$this->league        = key_exists('league', $teamData) ? trim($teamData['league'] ?: "") : "";
		$this->logo          = trim($teamData['team_logo']) ?: null;
		$this->logo_inverted = key_exists('team_logo_inverted', $teamData) && $teamData['team_logo_inverted'] ? trim($teamData['team_logo_inverted']) : "";
		$this->color         = trim($teamData['team_color']) ?: "#000000";
		$this->position      = key_exists("position_id", $teamData) && $teamData['position_id'] ? new PositionModel($teamData) : null;
		$this->image         = key_exists("image", $teamData) && $teamData['image'] ? trim($teamData['image']) : "";
		$this->since         = key_exists("since", $teamData) && $teamData['since'] ? trim($teamData['since']) : "";
		$this->until         = key_exists("until", $teamData) && $teamData['until'] ? trim($teamData['until']) : "";
		$this->number        = isset($teamData['number']) ? intval($teamData['number']) : null;
		$this->hidden        = $this->defineVisibility($only_active_pos);
	}

	/**
	 * Determines the visibility status based on the provided parameter and internal state.
	 *
	 * @param   bool  $only_active_pos  Specifies whether to check only active positions.
	 *
	 * @return bool Returns true if the conditions for visibility are met; otherwise, false.
	 * @since 2.0.0
	 */
	private function defineVisibility(bool $only_active_pos): bool
	{
		if (!$only_active_pos) return false;
		if (!$this->until) return false;
		$date  = new Date($this->until);
		$today = new Date('now');

		return !($date > $today);
	}
}