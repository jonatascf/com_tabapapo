<?php
/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.8.5
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

HTMLHelper::_('behavior.multiselect');

$this->addToolBar();

$user      = Factory::getUser();
$userId    = $user->get('id');
$listOrder     = $this->escape($this->state->get('list.ordering'));
$listDirn      = $this->escape($this->state->get('list.direction'));
//$assoc     = Associations::isEnabled();



?>

<div class="row">
	<form action="index.php?option=com_tabapapo&view=tabapapo" method="post" id="adminForm" name="adminForm">
      <div class="col-md-12">
         <div id="j-main-container">
				<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
				<?php if (empty($this->items)) : ?>
					<div class="alert alert-info">
						<span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
						<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
					</div>
				<?php else : ?>

            	<table class="table table-hover" id ="tabapapo-list">
						<caption class="visually-hidden">
							<?php echo Text::_('COM_TABAPAPO_TABLE_CAPTION'); ?>,
							<span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
							<span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
						</caption>

            		<thead>
            		<tr>
            			<th class="w-1 text-center">
									<?php echo HTMLHelper::_('grid.checkall'); ?>
							</th>
<!--							<th scope="col" class="w-1 text-center">
								<?php echo HTMLHelper::_('searchtools.sort', 'JFEATURED', 'a.featured', $listDirn, $listOrder); ?>
							</th> -->
							<th scope="col" class="w-1 text-center">
								<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
							</th>
							<th scope="col">
								<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
							</th>
<!----							<th scope="col" class="w-10 d-none d-md-table-cell">
								<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'access_level', $listDirn, $listOrder); ?>
							</th>
						<?php if ($assoc) : ?>
							<th scope="col" class="w-10">
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_CONTACT_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
							</th>
						<?php endif; ?>
						<?php if (Multilanguage::isEnabled()) : ?>
							<th scope="col" class="w-10 d-none d-md-table-cell">
								<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language_title', $listDirn, $listOrder); ?>
							</th>
						<?php endif; ?> -->
							<th scope="col" class="w-5 d-none d-md-table-cell">
								<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
							</th>
            		</tr>
            		</thead>
            		<tbody>
						<?php
						$n = count($this->items);
						foreach ($this->items as $i => $item) :
							$canCreate  = $user->authorise('core.create',     'com_tabapapo.category.' . $item->catid);
							$canEdit    = $user->authorise('core.edit',       'com_tabapapo.category.' . $item->catid);
							$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || is_null($item->checked_out);
							$canEditOwn = $user->authorise('core.edit.own',   'com_tabapapo.category.' . $item->catid) && $item->created_by == $userId;
							$canChange  = $user->authorise('core.edit.state', 'com_tabapapo.category.' . $item->catid) && $canCheckin;

							$item->cat_link = Route::_('index.php?option=com_categories&extension=com_tabapapo&task=edit&type=other&id=' . $item->catid);
                     $link = JRoute::_('index.php?option=com_tabapapo&task=tabapapoadd.edit&id=' . $item->id); ?>
            			<tr>
								<td class="text-center">
									<?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->name); ?>
								</td>
<!--      						<td class="text-center">
      							<?php //echo HTMLHelper::_('administrator.featured', $item->featured, $i, $canChange); ?>
      						</td> -->
								<td class="text-center">
									<?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'tabapapo.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
								</td>

								<td scope="row" class="has-context">
									<div>
										<?php if ($item->checked_out) : ?>
											<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'tabapapo.', $canCheckin); ?>
										<?php endif; ?>
										<?php if ($canEdit || $canEditOwn) : ?>
											<a href="<?php echo $link; ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->title); ?>">
												<?php echo $this->escape($item->title); ?></a>
										<?php else : ?>
											<?php echo $this->escape($item->title); ?>
										<?php endif; ?>
										<div class="small">
											<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
										</div>
										<div class="small">
											<?php echo Text::_('JCATEGORY') . ': ' . $this->escape($item->category_title); ?>
										</div>
									</div>
								</td>
<!--								<td class="small d-none d-md-table-cell">
									<?php echo $item->access_level; ?>
								</td>
							<?php if ($assoc) : ?>
								<td class="d-none d-md-table-cell">
									<?php if ($item->association) : ?>
										<?php //echo HTMLHelper::_('administrator.association', $item->id); ?>
									<?php endif; ?>
								</td>
							<?php endif; ?> 
							<?php if (Multilanguage::isEnabled()) : ?>
								<td class="small d-none d-md-table-cell">
									<?php echo LayoutHelper::render('joomla.tabapapo.language', $item); ?>
								</td>
							<?php endif; ?> -->
								<td class="d-none d-md-table-cell">
									<?php echo $item->id; ?>
								</td>
            			</tr>
      				<?php endforeach; ?>

         		</tbody>
         	</table>

               <?php echo $this->pagination->getListFooter(); ?>

      			<?php endif; ?>
            	<input type="hidden" name="task" value=""/>
            	<input type="hidden" name="boxchecked" value="0"/>
               <?php echo JHtml::_('form.token'); ?>

      </div>
   </div>
</form>
</div>

