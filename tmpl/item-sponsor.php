<?php
/**
 * @package                                     NXD Football Manager 2 People Module (mod_nxdfm2_people)
 *
 * @author                                      NXD | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by NXD nx-designs
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 *
 * @var $params  Joomla\CMS\Parameter\Parameter  The module parameters
 * @var $sponsor stdClass                        The sponsor object
 *
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

$hoverBoxShadowCls = $sponsor->website ? 'uk-box-shadow-hover-large' : '';

?>


<div class="uk-card uk-card-default uk-card-body uk-card-small uk-width-1-1 uk-position-relative uk-margin-auto <?php echo $hoverBoxShadowCls;?>">
    <div class="uk-flex uk-flex-middle uk-grid-small">
        <?php
        echo '<div class="nxd-sponsor-logo">';
        if ($sponsor->logo)
        {
            echo HTMLHelper::image($sponsor->logo, 'Sponsor Image', ['class' => 'nxd-person-sponsor-logo', 'height' => '50']);
        }
        echo '</div>';
        ?>
        <div>
            <?php echo $sponsor->title; ?>
        </div>
    </div>
	<?php if ($sponsor->website): ?>
        <a class="uk-position-cover" href="<?php echo $sponsor->website; ?>" target="_blank"></a>
	<?php endif; ?>
</div>