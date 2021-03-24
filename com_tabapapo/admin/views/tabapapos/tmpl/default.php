<?php
/**
 * @package     Tabapapo.Administrator
 * @subpackage  com_tabapapo
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\Registry\Registry;

JHtml::_('formbehavior.chosen', 'select');

$listOrder     = $this->escape($this->state->get('list.ordering'));
$listDirn      = $this->escape($this->state->get('list.direction'));
?>
<form action="index.php?option=com_tabapapo&view=tabapapos" method="post" id="adminForm" name="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo JHtmlSidebar::render(); ?>
	</div>
	<div id="j-main-container" class="span10">
	<div class="row-fluid">
			<?php
				echo JLayoutHelper::render(
					'joomla.searchtools.default',
					array('view' => $this)
				);
			?>
	</div>
	<table class="table table-striped table-hover">
		<thead>
		<tr>
			<th width="1%"><?php echo JText::_('COM_TABAPAPO_NUM'); ?></th>
			<th width="2%">
				<?php echo JHtml::_('grid.checkall'); ?>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'COM_TABAPAPO_TABAPAPOS_NAME', 'title', $listDirn, $listOrder); ?>
			</th>
			<th width="20%">
            <?php echo JHtml::_('grid.sort', 'COM_TABAPAPO_AUTHOR', 'created_by', $listDirn, $listOrder); ?>
         </th>
         <th width="20%">
            <?php echo JHtml::_('grid.sort', 'COM_TABAPAPO_CREATED_DATE', 'created', $listDirn, $listOrder); ?>
         </th>
         <th width="20%">
            <?php echo JText::_('COM_TABAPAPO_TABAPAPOS_IMAGE'); ?>
         </th>
			<th width="5%">
				<?php echo JHtml::_('grid.sort', 'COM_TABAPAPO_PUBLISHED', 'published', $listDirn, $listOrder); ?>
			</th>
			<th width="2%">
				<?php echo JHtml::_('grid.sort', 'COM_TABAPAPO_ID', 'id', $listDirn, $listOrder); ?>
			</th>
		</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php if (!empty($this->items)) : ?>
				<?php foreach ($this->items as $i => $row) : 
					$link = JRoute::_('index.php?option=com_tabapapo&task=tabapapo.edit&id=' . $row->id);
					$row->imagem = new Registry;
               $row->imagem->loadString($row->imagemInfo); ?>
					<tr>
						<td>
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
						<td>
							<?php echo JHtml::_('grid.id', $i, $row->id); ?>
						</td>
						<td>
							<a href="<?php echo $link; ?>" tile="<?php echo JText::_('COM_TABAPAPO_EDIT_TABAPAPO'); ?>">
							<?php echo $row->title; ?>
							</a>
							<div class="small">
								<?php echo JText::_('JCATEGORY') . ': ' . $this->escape($row->category_title); ?>
							</div>
						</td>
                  <td align="center">
                      <?php echo $row->created_by; ?>
                  </td>
                  <td align="center">
                      <?php echo substr($row->created, 0, 10); ?>
                  </td>                  
                  <td align="center">
                     <?php
                       $caption = $row->imagem->get('caption') ? : '' ;
                       $src = JURI::root() . ($row->imagem->get('imagem') ? : '' );
                       $html = '<p class="hasTooltip" style="display: inline-block" data-html="true" data-toggle="tooltip" data-placement="right" title="<img width=\'100px\' height=\'100px\' src=\'%s\'>">%s</p>';
                       echo sprintf($html, $src, $caption);  ?>
                  </td>						
						<td align="center">
							<?php echo JHtml::_('jgrid.published', $row->published, $i, 'tabapapos.', true, 'cb'); ?>
						</td>
						<td align="center">
							<?php echo $row->id; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
   <?php echo JHtml::_('form.token'); ?>
	</div>
</form>