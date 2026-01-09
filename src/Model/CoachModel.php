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

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\Registry\Registry;
use stdClass;

class CoachModel extends PersonModel
{

	public array $custom_fields = [];
	protected string $type = 'coach';

	public function __construct(stdClass $personData, Registry $params)
	{
		$this->countries_table = '#__footballmanager_coaches_countries';
		$this->custom_fields = $this->getCustomFields();

		parent::__construct($personData, $params);

	}
}