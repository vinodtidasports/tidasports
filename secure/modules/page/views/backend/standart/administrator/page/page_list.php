<section class="content-header">
   <h1>
      <?= cclang('page'); ?><small><?= cclang('list_all'); ?></small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> <?= cclang('home'); ?></a></li>
      <li class="active"><?= cclang('page'); ?></li>
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
                        <?php is_allowed('page_add', function () { ?>
                           <a class="btn btn-flat btn-success btn_add_new" id="btn_add_new" title="<?= cclang('add_new_button', 'Page'); ?> (Ctrl+a)" href="<?= admin_site_url('/page/add'); ?>"><i class="fa fa-plus-square-o"></i> <?= cclang('add_new_button', 'Page'); ?></a>
                        <?php }) ?>
                        <?php is_allowed('page_export', function () { ?>
                           <a class="btn btn-flat btn-success" title="<?= cclang('export', 'Page'); ?>" href="<?= admin_site_url('/page/export'); ?>"><i class="fa fa-file-excel-o"></i> <?= cclang('export', 'Page'); ?></a>
                        <?php }) ?>
                     </div>
                     <div class="widget-user-image">
                        <img class="img-circle" src="<?= BASE_ASSET ?>img/list.png" alt="User Avatar">
                     </div>

                     <h3 class="widget-user-username">Page</h3>
                     <h5 class="widget-user-desc"><?= cclang('list_all', 'Page'); ?> <i class="label bg-yellow"><?= $page_counts; ?> <?= cclang('items', 'Page'); ?></i></h5>
                  </div>
                  <form name="form_page" id="form_page" action="<?= admin_base_url('/page/index'); ?>">
                     <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable">
                           <thead>
                              <tr>
                                 <th>
                                    <input type="checkbox" class="flat-red toltip" id="check_all" name="check_all" title="check all">
                                 </th>
                                 <th data-field="title" data-sort="1"><?= cclang('title'); ?></th>
                                 <th data-field="type" data-sort="1"><?= cclang('type'); ?></th>
                                 <th data-field="link" data-sort="1"><?= cclang('link'); ?></th>
                                 <th data-field="template" data-sort="1"><?= cclang('template'); ?></th>
                                 <th data-field="created_at" data-sort="1"><?= cclang('created_at'); ?></th>
                                 <th><?= cclang('action'); ?></th>
                              </tr>
                           </thead>
                           <tbody id="tbody_page">
                              <?php foreach ($pages as $page) : ?>
                                 <tr>
                                    <td width="5">
                                       <input type="checkbox" class="flat-red check" name="id[]" value="<?= $page->id; ?>">
                                    </td>
                                    <td><?= _ent($page->title); ?></td>
                                    <td><?= _ent($page->type); ?></td>
                                    <td>
                                       <?php
                                       if ($page->type == 'backend') {
                                          $view_url = ADMIN_NAMESPACE_URL.'/page/detail/' . $page->link;
                                       } elseif ($page->type == 'frontend') {
                                          $view_url = 'page/' . $page->link;
                                       }
                                       echo anchor($view_url, '<i class="fa fa-chain"></i> ', ['target' => 'blank']) . ' ' . $page->link;
                                       ?></td>
                                    <td><?= _ent($page->template); ?></td>
                                    <td><?= _ent($page->created_at); ?></td>
                                    <td width="200">
                                       <?php is_allowed('page_view', function () use ($page, $view_url) { ?>
                                          <a href="<?= site_url($view_url); ?>" class="label-default"><i class="fa fa-newspaper-o"></i> <?= cclang('view_button'); ?>
                                          <?php }) ?>
                                          <?php is_allowed('page_update', function () use ($page) { ?>
                                             <a href="<?= admin_site_url('/page/edit/' . $page->id); ?>" class="label-default"><i class="fa fa-edit "></i> <?= cclang('update_button'); ?></a>
                                          <?php }) ?>
                                          <?php is_allowed('page_delete', function () use ($page) { ?>
                                             <a href="javascript:void(0);" data-href="<?= admin_site_url('/page/delete/' . $page->id); ?>" class="label-default remove-data"><i class="fa fa-close"></i> <?= cclang('remove_button'); ?></a>
                                          <?php }) ?>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                              <?php if ($page_counts == 0) : ?>
                                 <tr>
                                    <td colspan="100">
                                       <?= cclang('data_is_not_avaiable', 'Page'); ?>
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
                           <option value="">Bulk</option>
                           <option value="delete"><?= cclang('delete'); ?></option>
                        </select>
                     </div>
                     <div class="col-sm-2 padd-left-0 ">
                        <button type="button" class="btn btn-flat" name="apply" id="apply" title="apply bulk actions"><?= cclang('apply_button', 'Page'); ?></button>
                     </div>
                     <div class="col-sm-3 padd-left-0  ">
                        <input type="text" class="form-control" name="q" id="filter" placeholder="<?= cclang('filter'); ?>" value="<?= $this->input->get('q'); ?>">
                     </div>
                     <div class="col-sm-3 padd-left-0 ">
                        <select type="text" class="form-control chosen chosen-select" name="f" id="field">
                           <option value=""><?= cclang('all'); ?></option>
                           <option <?= $this->input->get('f') == 'title' ? 'selected' : ''; ?> value="title">Title</option>
                           <option <?= $this->input->get('f') == 'type' ? 'selected' : ''; ?> value="type">Type</option>
                           <option <?= $this->input->get('f') == 'link' ? 'selected' : ''; ?> value="link">Link</option>
                           <option <?= $this->input->get('f') == 'created_at' ? 'selected' : ''; ?> value="created_at">Created At</option>
                        </select>
                     </div>
                     <div class="col-sm-1 padd-left-0 ">
                        <button type="submit" class="btn btn-flat" name="sbtn" id="sbtn" value="Apply" title="<?= cclang('filter_search'); ?>">
                           <?= cclang('filter_button'); ?>
                        </button>
                     </div>
                     <div class="col-sm-1 padd-left-0 ">
                        <a class="btn btn-default btn-flat" name="reset" id="reset" value="Apply" href="<?= admin_base_url('/page'); ?>" title="<?= cclang('reset_filter'); ?>">
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


<script src="<?= BASE_ASSET ?>js/page/page/page-list.js"></script>