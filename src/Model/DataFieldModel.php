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

class DataFieldModel {
	public string $title;
	public string $value;
	public function __construct(string $title, string $value)
	{
		$this->title = $title;
		$this->value = $value;
	}
}