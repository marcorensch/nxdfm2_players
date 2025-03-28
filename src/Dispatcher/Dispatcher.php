<?php
/**
 * @package                                     NXD Football Manager 2 People Module (mod_nxdfm2_people)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 * @var $settings Joomla\CMS\Parameter\Parameter  The module parameters
 * @var $player   stdClass                        The player object
 *
 */


namespace NXD\Module\FootballManagerPeople\Site\Dispatcher;

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Joomla\CMS\MVC\Factory\MVCFactory;
use Joomla\Registry\Registry;
use NXD\Module\FootballManagerPeople\Site\Model\PlayersModel;
use NXD\Module\FootballManagerPeople\Site\Model\CoachesModel;
use NXD\Module\FootballManagerPeople\Site\Model\CheerleadersModel;
use NXD\Module\FootballManagerPeople\Site\Model\TeamMembersModel;


class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
	use HelperFactoryAwareTrait;

	protected function getLayoutData(): false|array
	{
		$params = new Registry($this->module->params);
		$app    = $this->getApplication();
		$data   = parent::getLayoutData();

		if ($data === false) return false;

		$config = array();
		$model  = $this->getModel($params->get('context', 'players'), $config);

		if (!$model)
		{
			throw new \RuntimeException("Model for {$params->get('context','')} not found.");
		}

		// Set Module States
		$model->setModuleStates($params);

		$data['team'] = $model->getTeamMembers($params) ?? array();

		return $data;
	}

	/**
	 * @throws \Exception
	 * @since 2.0.0
	 */
	private function getModel(string $context, array $config): TeamMembersModel|null
	{
		return match ($context)
		{
			'players' => new PlayersModel($config, null),
			'coaches' => new CoachesModel($config, null),
			'cheerleaders' => new CheerleadersModel($config, null),
			default => null,
		};

	}
}