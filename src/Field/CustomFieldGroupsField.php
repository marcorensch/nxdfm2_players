<?php
/**
 * @package                                     NXD Football Manager 2 Players Module (mod_nxdfm2_players)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 */

namespace NXD\Module\FootballManagerPlayers\Site\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Database\DatabaseInterface;

class CustomFieldGroupsField extends ListField
{
	protected $type = 'customfieldgroups';
	protected function getLabel()
	{
		return parent::getLabel(); // TODO: Change the autogenerated stub
	}

	protected function getOptions()
	{
		// Get the Field Groups
		$fieldGroups = $this->getFieldGroups('com_footballmanager.player');

		$options = [];
		foreach ($fieldGroups as $fieldGroup)
		{
			$options[] = HTMLHelper::_('select.option', $fieldGroup->id, $fieldGroup->title);
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

	private function getFieldGroups($context): array
	{
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		$query = $db->getQuery(true);
		$query->select('id, title');
		$query->from('#__fields_groups');
		$query->where('context = ' . $db->quote($context));
		$query->order('ordering ASC');
		$db->setQuery($query);
		$fieldGroups = $db->loadObjectList();

		return $fieldGroups;
	}

}