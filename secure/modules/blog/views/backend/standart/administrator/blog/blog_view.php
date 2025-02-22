<section class="content-header">
   <h1>
      Blog <small><?= cclang('detail', ['Blog']); ?> </small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?= admin_site_url('/blog'); ?>">Blog</a></li>
      <li class="active"><?= cclang('detail'); ?></li>
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
                        <img class="img-circle" src="<?= BASE_ASSET ?>img/view.png" alt="User Avatar">
                     </div>

                     <h3 class="widget-user-username">Blog</h3>
                     <h5 class="widget-user-desc">Detail Blog</h5>
                     <hr>
                  </div>

                  <div class="form-horizontal" name="form_blog" id="form_blog">

                     <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Id </label>

                        <div class="col-sm-8">
                           <?= _ent($blog->id); ?>
                        </div>
                     </div>

                     <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Title </label>

                        <div class="col-sm-8">
                           <?= _ent($blog->title); ?>
                        </div>
                     </div>

                     <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Slug </label>

                        <div class="col-sm-8">
                           <?= _ent($blog->slug); ?>
                        </div>
                     </div>

                     <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Content </label>

                        <div class="col-sm-8">
                           <?= _ent($blog->content); ?>
                        </div>
                     </div>

                     <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label"> Image </label>
                        <div class="col-sm-8">
                           <?php if (!empty($blog->image)) : ?>
                              <?php foreach (explode(',', $blog->image) as $filename) : ?>
                                 <?php if (is_image($blog->image)) : ?>
                                    <a class="fancybox" rel="group" href="<?= BASE_URL . 'uploads/blog/' . $filename; ?>">
                                       <img src="<?= BASE_URL . 'uploads/blog/' . $filename; ?>" class="image-responsive" alt="image blog" title="image blog" width="40px">
                                    </a>
                                 <?php else : ?>
                                    <label>
                                       <a href="<?= ADMIN_BASE_URL . '/file/download/blog/' . $filename; ?>">
                                          <img src="<?= get_icon_file($filename); ?>" class="image-responsive" alt="image blog" title="image <?= $filename; ?>" width="40px">
                                          <?= $filename ?>
                                       </a>
                                    </label>
                                 <?php endif; ?>
                              <?php endforeach; ?>
                           <?php endif; ?>
                        </div>
                     </div>

                     <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Tags </label>

                        <div class="col-sm-8">
                           <?= _ent($blog->tags); ?>
                        </div>
                     </div>

                     <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Category </label>

                        <div class="col-sm-8">
                           <?= _ent($blog->category_name); ?>
                        </div>
                     </div>

                     <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Status </label>

                        <div class="col-sm-8">
                           <?= _ent($blog->status); ?>
                        </div>
                     </div>

                     <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Author </label>

                        <div class="col-sm-8">
                           <?= _ent($blog->author); ?>
                        </div>
                     </div>

                     <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Created At </label>

                        <div class="col-sm-8">
                           <?= _ent($blog->created_at); ?>
                        </div>
                     </div>

                     <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Updated At </label>

                        <div class="col-sm-8">
                           <?= _ent($blog->updated_at); ?>
                        </div>
                     </div>

                     <br>
                     <br>

                     <div class="view-nav">
                        <?php is_allowed('blog_update', function () use ($blog) { ?>
                           <a class="btn btn-flat btn-info btn_edit btn_action" id="btn_edit" data-stype='back' title="edit blog (Ctrl+e)" href="<?= admin_site_url('/blog/edit/' . $blog->id); ?>"><i class="fa fa-edit"></i> <?= cclang('update', ['Blog']); ?> </a>
                        <?php }) ?>
                        <a class="btn btn-flat btn-default btn_action" id="btn_back" title="back (Ctrl+x)" href="<?= admin_site_url('/blog/'); ?>"><i class="fa fa-undo"></i> <?= cclang('go_list_button', ['Blog']); ?></a>
                     </div>

                  </div>
               </div>
            </div>

         </div>


      </div>
   </div>
</section>