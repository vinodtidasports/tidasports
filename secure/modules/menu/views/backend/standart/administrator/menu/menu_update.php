<link rel="stylesheet" href="<?= BASE_ASSET ?>css/menu.css">

<?php $this->load->view('core_template/fine_upload'); ?>

<section class="content-header">
  <h1>
    <?= cclang('menu') ?>
    <small><?= cclang('update_button', cclang('menu')) ?></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> <?= cclang('home') ?></a></li>
    <li><a href="<?= admin_site_url('/menu'); ?>"> <?= cclang('menu') ?></a></li>
    <li class="active"> <?= cclang('update_button') ?></li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-body ">

          <div class="box box-widget widget-user-2">

            <div class="widget-user-header ">
              <div class="widget-user-image">
                <img class="img-circle" src="<?= BASE_ASSET ?>img/add2.png" alt="User Avatar">
              </div>

              <h3 class="widget-user-username"> <?= cclang('menu') ?></h3>
              <h5 class="widget-user-desc"> <?= cclang('new', cclang('menu')) ?></h5>
              <hr>
            </div>

            <?= form_open(ADMIN_NAMESPACE_URL . '/menu/edit_save/' . $this->uri->segment(4), [
              'name'    => 'form_menu',
              'class'   => 'form-horizontal',
              'id'      => 'form_menu',
              'method'  => 'POST'
            ]); ?>
            <input type="hidden" value="<?= $menu->menu_type_id; ?>" name="menu_type_id">

            <div class="form-group">
              <label for="content" class="col-sm-2 control-label"> <?= cclang('icon') ?> </label>

              <div class="col-sm-8">
                <input type="hidden" name="icon" id="icon" value="<?= $menu->icon; ?>">

                <div class="icon-preview <?= $menu->icon_color; ?>">
                  <span class="icon-preview-item"><i class="fa <?= $menu->icon; ?> fa-lg"></i></span>
                </div>
                <br>
                <br>

                <a class="btn btn-default btn-select-icon btn-flat"> <?= cclang('select_icon') ?></a>

                <select class="chosen  chosen-select-deselect" name="icon_color" id="icon_color" tabi-ndex="5" data-placeholder="Select Color">
                  <option value="default">Default</option>
                  <?php foreach ($color_icon as $color) : ?>
                    <option value="<?= $color; ?>" <?= $menu->icon_color == $color ? 'selected' : ''; ?>><?= ucwords(str_replace('-', ' ', $color)); ?></option>
                  <?php endforeach; ?>
                </select>

              </div>
            </div>


            <div class="form-group ">
              <label for="content" class="col-sm-2 control-label"> <?= cclang('parent') ?></label>

              <div class="col-sm-8">
                <select class="form-control chosen  chosen-select-deselect" name="parent" id="parent" tabi-ndex="5" data-placeholder="Select Parent">
                  <option value=""></option>

                  <?php foreach (get_menu($menu->menu_type_id) as $row) : ?>
                    <option <?= $row->id == $menu->parent ? 'selected="selected"' : ''; ?> value="<?= $row->id; ?>"><?= ucwords($row->label); ?></option>
                    <?php if (isset($row->children) and count($row->children)) : ?>
                      <?php create_childern($row->children, $menu->parent, 1); ?>
                    <?php endif ?>
                  <?php endforeach; ?>
                </select>
                <small class="info help-block">
                  Select one or more groups.
                </small>
              </div>
            </div>
            <div class="form-group ">
              <label for="label" class="col-sm-2 control-label"> <?= cclang('label') ?> <i class="required">*</i></label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="label" id="label" placeholder="Label" value="<?= set_value('label', $menu->label); ?>">
                <small class="info help-block">The label of menu.</small>
              </div>
            </div>


            <div class="form-group ">
              <label for="link" class="col-sm-2 control-label"> <?= cclang('link') ?> <i class="required">*</i></label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="link" id="link" placeholder="Link" value="<?= set_value('link', $menu->link); ?>">
                <small class="info help-block">The link of menu <i>Example : administrator/blog</i>. short code <code>{admin_url}</code></small>
              </div>
            </div>

            <div class="form-group ">
              <label for="content" class="col-sm-2 control-label"> <?= cclang('menu_type') ?></label>

              <div class="col-sm-8">
                <label class="col-md-2 padding-left-0">
                  <input <?= $menu->type == 'menu' ? 'checked' : ''; ?> type="radio" name="type" class="flat-green menu_type" value="menu"> <?= cclang('menu'); ?>
                </label>
                <label>
                  <input <?= $menu->type == 'label' ? 'checked' : ''; ?> type="radio" name="type" class="flat-green menu_type" value="label"> <?= cclang('label'); ?>
                </label>
                <small class="info help-block">
                  Type Of Menu.
                </small>
              </div>
            </div>

            <div class="form-group ">
              <label for="content" class="col-sm-2 control-label"> <?= cclang('group_privilage') ?></label>

              <div class="col-sm-8">
                <select class="form-control chosen chosen-select" name="group[]" id="group" tabi-ndex="5" data-placeholder="Select Groups" multiple="">
                  <option value=""></option>
                  <?php foreach (db_get_all_data('aauth_groups') as $row) : ?>
                    <option <?= array_search($row->id, $group_menu) !== false ? 'selected="selected"' : ''; ?> value="<?= $row->id; ?>"><?= ucwords($row->name); ?></option>
                  <?php endforeach; ?>
                </select>

                </select>
                <small class="info help-block">

                  group is allowed to access this menu.
                </small>
              </div>
            </div>


            <div class="message">
            </div>
            <div class="row-fluid col-md-7">
              <button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay' title="save (Ctrl+s)"><i class="fa fa-save"></i> <?= cclang('save_button'); ?></button>
              <a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save" data-stype='back' title="save and back to the list (Ctrl+d)"><i class="ion ion-ios-list-outline"></i> <?= cclang('save_and_go_the_list_button'); ?> List</a>
              <a class="btn btn-flat btn-default btn_action" id="btn_cancel" title="cancel (Ctrl+x)"><i class="fa fa-undo"></i> <?= cclang('cancel_button'); ?></a>
              <span class="loading loading-hide"><img src="<?= BASE_ASSET ?>img/loading-spin-primary.svg"> <i>Loading, Saving data</i></span>
            </div>
            <?= form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<div class="modal fade " tabindex="-1" role="dialog" id="modalIcon">
  <div class="modal-dialog full-width" role="document">
    <div class="modal-content">

      <div class="modal-body">
        <?php $this->load->view('backend/standart/administrator/menu/partial_icon'); ?>

      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<script src="<?= BASE_ASSET ?>ckeditor/ckeditor.js"></script>
<script>
  "use strict";

  window.parentid = '<?= $this->input->get('parent') ?>';
</script>

<script src="<?= BASE_ASSET ?>js/page/menu/menu-update.js"></script>