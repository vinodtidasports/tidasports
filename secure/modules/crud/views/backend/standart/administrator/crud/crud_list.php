<script src="<?= BASE_ASSET ?>js/jquery.hotkeys.js"></script>

<section class="content-header">
   <h1>
      Crud<small><?= cclang('list_all'); ?></small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> <?= cclang('home') ?></a></li>
      <li class="active">Crud</li>
   </ol>
</section>

<section class="content">
   <div class="row">

      <div class="col-md-12">
         <div class="box box-warning">
            <div class="box-body ">

               <div class="box box-widget widget-user-2">

                  <div class="widget-user-header ">
                     <div class="row pull-right">
                        <?php is_allowed('crud_add', function () { ?>
                           <a class="btn btn-flat btn-success btn_add_new" id="btn_add_new" title="<?= cclang('add_new_button', ['Crud']); ?> (Ctrl+a)" href="<?= admin_site_url('crud/add'); ?>"><i class="fa fa-plus-square-o"></i> <?= cclang('add_new_button', ['Crud']); ?></a>
                        <?php }) ?>
                        <?php is_allowed('crud_export', function () { ?>
                           <a class="btn btn-flat btn-success" title="<?= cclang('export', ['Crud']); ?>" href="<?= admin_site_url('/crud/export'); ?>"><i class="fa fa-file-excel-o"></i> <?= cclang('export_button'); ?></a>
                        <?php }) ?>
                     </div>
                     <div class="widget-user-image">
                        <img class="img-circle" src="<?= BASE_ASSET ?>img/list.png" alt="User Avatar">
                     </div>

                     <h3 class="widget-user-username">Crud</h3>
                     <h5 class="widget-user-desc"><?= cclang('list_all', ['Crud']); ?> <i class="label bg-yellow"><?= $crud_counts; ?> <?= cclang('items'); ?></i></h5>
                  </div>

                  <form name="form_crud" id="form_crud" action="<?= admin_base_url('/crud/index'); ?>">
                     <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable">
                           <thead>
                              <tr>
                                 <th>
                                    <input type="checkbox" class="flat-red toltip" id="check_all" name="check_all" title="check all">
                                 </th>
                                 <th data-field="title" data-primary-key="0" data-sort="1"><?= cclang('title') ?></th>
                                 <th data-field="subject" data-primary-key="0" data-sort="1"><?= cclang('subject') ?></th>
                                 <th data-field="table_name" data-primary-key="0" data-sort="1"><?= cclang('table_name') ?></th>
                                 <th><?= cclang('action') ?></th>
                              </tr>
                           </thead>
                           <tbody id="tbody_crud">
                              <?php foreach ($cruds as $crud) : ?>
                                 <tr>
                                    <td width="5">
                                       <input type="checkbox" class="flat-red check" name="id[]" value="<?= $crud->id; ?>">
                                    </td>
                                    <td><?= _ent($crud->title); ?></td>
                                    <td><?= _ent($crud->subject); ?></td>
                                    <td><?= _ent($crud->table_name); ?>
                                       <?php
                                       $new_field = $this->model_crud->get_new_field($crud->id);
                                       if (count($new_field)) : ?>
                                          <span class="label label-info"><i class="fa fa-info-circle"></i> new <?= count($new_field); ?> field need to update</span>
                                       <?php endif; ?>
                                    </td>
                                    <td width="200">
                                       <?php is_allowed('crud_view', function () use ($crud) { ?>
                                          <a href="<?= admin_site_url('' . $crud->table_name); ?>" class="label-default"><i class="fa fa-newspaper-o"></i> <?= cclang('view_button'); ?>
                                          <?php }) ?>
                                          <?php is_allowed('crud_update', function () use ($crud) { ?>
                                             <a href="<?= admin_site_url('crud/edit/' . $crud->id); ?>" class="label-default"><i class="fa fa-edit "></i> <?= cclang('update_button'); ?></a>
                                          <?php }) ?>
                                          <?php is_allowed('crud_delete', function () use ($crud) { ?>
                                             <a href="javascript:void(0);" data-href="<?= admin_site_url('/crud/delete/' . $crud->id); ?>" class="label-default remove-data"><i class="fa fa-close"></i> <?= cclang('remove_button'); ?></a>
                                          <?php }) ?>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                              <?php if ($crud_counts == 0) : ?>
                                 <tr>
                                    <td colspan="100">
                                       <?= cclang('data_is_not_avaiable', ['Crud']); ?>
                                    </td>
                                 </tr>
                              <?php endif; ?>
                           </tbody>
                        </table>
                     </div>
               </div>
               <hr>

               <div class="row">
                  <div class="col-md-8">
                     <div class="col-sm-2 padd-left-0 ">
                        <select type="text" class="form-control chosen chosen-select" name="bulk" id="bulk" placeholder="Site Email">
                           <option value=""><?= cclang('bulk') ?></option>
                           <option value="delete"><?= cclang('delete'); ?></option>
                        </select>
                     </div>
                     <div class="col-sm-2 padd-left-0 ">
                        <button type="button" class="btn btn-flat" name="apply" id="apply" title="<?= cclang('apply_bulk_action'); ?>"><?= cclang('apply_button'); ?></button>
                     </div>
                     <div class="col-sm-3 padd-left-0  ">
                        <input type="text" class="form-control" name="q" id="filter" placeholder="<?= cclang('filter'); ?>" value="<?= $this->input->get('q'); ?>">
                     </div>
                     <div class="col-sm-3 padd-left-0 ">
                        <select type="text" class="form-control chosen chosen-select" name="f" id="field">
                           <option value=""><?= cclang('all'); ?></option>
                           <option <?= $this->input->get('f') == 'title' ? 'selected' : ''; ?> value="title">Title</option>
                           <option <?= $this->input->get('f') == 'subject' ? 'selected' : ''; ?> value="subject">Subject</option>
                           <option <?= $this->input->get('f') == 'table_name' ? 'selected' : ''; ?> value="table_name">Table Name</option>
                        </select>
                     </div>
                     <div class="col-sm-1 padd-left-0 ">
                        <button type="submit" class="btn btn-flat" name="sbtn" id="sbtn" value="Apply" title="<?= cclang('filter_search'); ?>">
                           <?= cclang('filter_button'); ?>
                        </button>
                     </div>
                     <div class="col-sm-1 padd-left-0 ">
                        <a class="btn btn-default btn-flat" name="reset" id="reset" value="Apply" href="<?= admin_base_url('/crud'); ?>" title=" <?= cclang('reset_filter'); ?>">
                           <i class="fa fa-undo"></i>
                        </a>
                     </div>
                  </div>
                  </form>
                  <div class="col-md-4">
                     <div class="dataTables_paginate paging_simple_numbers pull-right" id="example2_paginate">
                        <?= $pagination; ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>

      </div>
   </div>
</section>

<script src="<?= BASE_ASSET ?>js/page/crud/crud-list.js"></script>