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

use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Registry\Registry;
use stdClass;

defined('_JEXEC') or die;

class PlayerModel extends PersonModel
{
	public ?int $age;
	public ?int $height;
	public ?int $weight;
	public array $custom_fields = [];
	protected string $type = 'player';

	public function __construct(stdClass $personData, Registry $params)
	{
		parent::__construct($personData, $params);

		$this->age           = self::calculateAge($personData->birthday);
		$this->height        = $personData->height;
		$this->weight        = $personData->weight;
		$this->custom_fields = $this->getCustomFields();

	}

}
