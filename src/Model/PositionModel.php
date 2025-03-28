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

defined('_JEXEC') or die;

class PositionModel
{
	public int $id = 0;
	public string $name;
	public string $description;
	public string $link;

	public function __construct(array $teamData)
	{
		$this->id          = $teamData['position_id'] ?? 0;
		$this->name        = $teamData['position_name'] ? trim($teamData['position_name']) : "";
		$this->description = $teamData['position_description'] ? trim($teamData['position_description']) : "";
		$this->link        = $teamData['position_link'] ? trim($teamData['position_link']) : "";
	}
}