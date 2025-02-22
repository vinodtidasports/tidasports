<section class="content-header">
   <h1>
      API Keys<small><?= cclang('list_all'); ?></small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">API Keys</li>
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
                        <?php is_allowed('keys_add', function () { ?>
                           <a class="btn btn-flat btn-success btn_add_new" id="btn_add_new" title="<?= cclang('add_new_button', 'API Keys'); ?> (Ctrl+a)" href="<?= admin_site_url('/keys/add'); ?>"><i class="fa fa-plus-square-o"></i> <?= cclang('add_new_button', 'API Keys'); ?></a>
                        <?php }) ?>
                        <?php is_allowed('keys_export', function () { ?>
                           <a class="btn btn-flat btn-success" title="<?= cclang('export', 'Keys'); ?>" href="<?= admin_site_url('/keys/export'); ?>"><i class="fa fa-file-excel-o"></i> <?= cclang('export', 'Excel'); ?></a>
                        <?php }) ?>
                        <?php is_allowed('keys_export', function () { ?>
                           <a class="btn btn-flat btn-success" title="<?= cclang('export', 'PDF Keys'); ?>" href="<?= admin_site_url('/keys/export_pdf'); ?>"><i class="fa fa-file-pdf-o"></i> <?= cclang('export', 'PDF'); ?></a>
                        <?php }) ?>
                     </div>
                     <div class="widget-user-image">
                        <img class="img-circle" src="<?= BASE_ASSET ?>img/list.png" alt="User Avatar">
                     </div>
                     <h3 class="widget-user-username">API Keys</h3>
                     <h5 class="widget-user-desc" <?= cclang('list_all', 'API Keys'); ?><i class="label bg-yellow"><?= $keys_counts; ?> items</i></h5>
                  </div>

                  <form name="form_keys" id="form_keys" action="<?= admin_base_url('/keys/index'); ?>">
                     <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable">
                           <thead>
                              <tr>
                                 <th>
                                    <input type="checkbox" class="flat-red toltip" id="check_all" name="check_all" title="check all">
                                 </th>
                                 <th data-field="key" data-sort="1">Key</th>
                                 <th data-field="is_private_key" data-sort="1">Is Private Key</th>
                                 <th data-field="ip_addresses" data-sort="1">Ip Addresses</th>
                                 <th data-field="date_created" data-sort="1">Date Created</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody id="tbody_keys">
                              <?php foreach ($keyss as $keys) : ?>
                                 <tr>
                                    <td width="5">
                                       <input type="checkbox" class="flat-red check" name="id[]" value="<?= $keys->id; ?>">
                                    </td>
                                    <td><?= _ent($keys->key); ?></td>
                                    <td><?= _ent($keys->is_private_key) ? 'yes' : 'no'; ?></td>
                                    <td><?= _ent($keys->ip_addresses); ?></td>
                                    <td><?= _ent($keys->date_created); ?></td>
                                    <td width="200">
                                       <?php is_allowed('keys_view', function () use ($keys) { ?>
                                          <a href="<?= admin_site_url('/keys/view/' . $keys->id); ?>" class="label-default"><i class="fa fa-newspaper-o"></i> <?= cclang('view_button'); ?>
                                          <?php }) ?>
                                          <?php is_allowed('keys_update', function () use ($keys) { ?>
                                             <a href="<?= admin_site_url('/keys/edit/' . $keys->id); ?>" class="label-default"><i class="fa fa-edit "></i> <?= cclang('update_button'); ?></a>
                                          <?php }) ?>
                                          <?php is_allowed('keys_delete', function () use ($keys) { ?>
                                             <a href="javascript:void(0);" data-href="<?= admin_site_url('/keys/delete/' . $keys->id); ?>" class="label-default remove-data"><i class="fa fa-close"></i> <?= cclang('remove_button'); ?></a>
                                          <?php }) ?>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                              <?php if ($keys_counts == 0) : ?>
                                 <tr>
                                    <td colspan="100">
                                       <?= cclang('data_is_not_avaiable', 'Keys'); ?>
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
                        <button type="button" class="btn btn-flat" name="apply" id="apply" title="<?= cclang('apply_bulk_action'); ?>"><?= cclang('apply_button'); ?></button>
                     </div>
                     <div class="col-sm-3 padd-left-0  ">
                        <input type="text" class="form-control" name="q" id="filter" placeholder="<?= cclang('filter'); ?>" value="<?= $this->input->get('q'); ?>">
                     </div>
                     <div class="col-sm-3 padd-left-0 ">
                        <select type="text" class="form-control chosen chosen-select" name="f" id="field">
                           <option value=""><?= cclang('all'); ?></option>
                           <option <?= $this->input->get('f') == 'key' ? 'selected' : ''; ?> value="key">Key</option>
                           <option <?= $this->input->get('f') == 'level' ? 'selected' : ''; ?> value="level">Level</option>
                           <option <?= $this->input->get('f') == 'ignore_limits' ? 'selected' : ''; ?> value="ignore_limits">Ignore Limits</option>
                           <option <?= $this->input->get('f') == 'is_private_key' ? 'selected' : ''; ?> value="is_private_key">Is Private Key</option>
                           <option <?= $this->input->get('f') == 'ip_addresses' ? 'selected' : ''; ?> value="ip_addresses">Ip Addresses</option>
                           <option <?= $this->input->get('f') == 'date_created' ? 'selected' : ''; ?> value="date_created">Date Created</option>
                        </select>
                     </div>
                     <div class="col-sm-1 padd-left-0 ">
                        <button type="submit" class="btn btn-flat" name="sbtn" id="sbtn" value="<?= cclang('apply_button'); ?>" title="<?= cclang('filter_search'); ?>">
                           Filter
                        </button>
                     </div>
                     <div class="col-sm-1 padd-left-0 ">
                        <a class="btn btn-default btn-flat" name="reset" id="reset" value="Apply" href="<?= admin_base_url('/keys'); ?>" title="<?= cclang('reset_filter'); ?>">
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

<script src="<?= BASE_ASSET ?>js/page/keys/keys-list.js"></script>