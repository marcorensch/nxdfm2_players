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

defined('_JEXEC') or die;

class GridHelper
{
	public static function buildGridClassnames($params) :string
	{

		$gridClassnames = GridHelper::buildColumnsString($params);
		$gridClassnames .= GridHelper::buildGridGapString($params);
		$gridClassnames .= GridHelper::buildGridAlignmentsString($params);

		return $gridClassnames;

	}

	private static function buildColumnsString($params) :string
	{
		$sizes = ['', 's', 'm', 'l', 'xl'];
		// Build Grid ColumnsString
		$gridColumns = '';
		foreach ($sizes as $size)
		{
			if($size != '')
			{
				$gridColumns .= 'uk-child-width-1-' . $params->get('grid_columns_' . $size, '1');
				$gridColumns .= '@' . $size;
			}else{
				$gridColumns .= 'uk-child-width-1-' . $params->get('grid_columns', '1');
			}
			$gridColumns .= ' ';
		}
		return $gridColumns;
	}

	private static function buildGridGapString($params) :string
	{
		$colGap = 'uk-grid-column-' . $params->get('grid_column_gap', 'small');
		$rowGap = 'uk-grid-row-' . $params->get('grid_row_gap', 'small');
		return $colGap . ' ' . $rowGap . ' ';
	}

	private static function buildGridAlignmentsString($params) :string
	{
		$sizes = ['', 's', 'm', 'l', 'xl'];
		$gridAlignments = '';
		foreach ($sizes as $size)
		{

			if($size != '')
			{
				$gridAlignments .= 'uk-flex-' . $params->get('grid_alignment_' . $size, 'center');
				$gridAlignments .= '@' . $size;
			}else{
				$gridAlignments .= 'uk-flex-' . $params->get('grid_alignment', 'center');
			}
			$gridAlignments .= ' ';
		}
		return $gridAlignments;
	}

}