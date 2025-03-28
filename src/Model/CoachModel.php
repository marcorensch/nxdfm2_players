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

use Joomla\Registry\Registry;
use stdClass;

class CoachModel extends PersonModel
{

	public array $custom_fields = [];
	protected string $type = 'coach';

	public function __construct(stdClass $personData, Registry $params)
	{
		parent::__construct($personData, $params);
		$this->custom_fields = $this->getCustomFields();
	}
}