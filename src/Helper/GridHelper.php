<?php

namespace NXD\Module\FootballManagerPlayers\Site\Helper;


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