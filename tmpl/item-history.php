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
 * @var $person stdClass                        The player object
 *
 */

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;


?>

<div id="<?php echo 'player-'.$person->id.'-history'?>" class="uk-margin-top">
    <h3 class="person-modal-title person-history-title"><?php echo Text::_('MOD_NXDFM2_PEOPLE_HISTORY_TITLE');?></h3>
    <table class="uk-table uk-table-striped uk-table-small uk-margin-top">
        <tbody>
        <?php foreach($person->effective->history as $team):
            if($team->historyHidden) continue;
            ?>
            <tr>
                <th class="uk-width-1-3">Team</th>
                <td class="uk-text-bold history-team-title">
                    <?php echo $team->title;?>
                    <?php if($team->league):?>
                        <span class="uk-text-meta uk-text-uppercase uk-text-muted">(<?php echo $team->league;?>)</span>
                    <?php endif;?>
                </td>
            </tr>
            <tr>
                <th class="uk-width-1-3">Number</th>
                <td class="history-team-number"><?php echo $team->number;?></td>
            </tr>
            <tr>
                <th class="uk-width-1-3">Position</th>
                <td class="history-team-position"><?php echo $team->position;?></td>
            </tr>
            <tr>
                <th class="uk-width-1-3">Since / Until</th>
                <td class="history-team-since-until"><?php
                    if($team->since){
                        echo HTMLHelper::date($team->since, Text::_($params->get('date_format','DATE_FORMAT_LC4')));
                    }
                    if($team->until)
                    {
                        echo ' - ' . HTMLHelper::date($team->until, Text::_($params->get('date_format', 'DATE_FORMAT_LC4')));
                    }
                    ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>