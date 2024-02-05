<?php
/**
 * @package                                     NXD Football Manager 2 Players Module (mod_nxdfm2_players)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 * @var $params Joomla\CMS\Parameter\Parameter  The module parameters
 * @var $player stdClass                        The player object
 *
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\Service\Provider\HelperFactory;
use Joomla\CMS\Extension\Service\Provider\Module;
use Joomla\CMS\Extension\Service\Provider\ModuleDispatcherFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class() implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$container->registerServiceProvider( new ModuleDispatcherFactory('\\NXD\\Module\\FootballManagerPlayers'));
		$container->registerServiceProvider( new HelperFactory('\\NXD\\Module\\FootballManagerPlayers\\Site\\Helper'));
		$container->registerServiceProvider( new Module());
	}
};