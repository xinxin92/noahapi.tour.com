<?php /*登录用户信息*/ ?>

<?php $__env->startSection('pagecss'); ?>
    <link rel="stylesheet" href="/assets/css/jquery.fancybox.css">
    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('content'); ?>
            <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">个人信息</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td width="20%" class='text-right '>注册时间:</td>
                            <td width="80%"><?php echo e($master['created_at']); ?></td>
                        </tr>
                        <tr>
                            <td class='text-right '>用户名:</td>
                            <td><?php echo e($master['master_name']); ?></td>
                        </tr>
                        <tr>
                            <td class='text-right '>姓名:</td>
                            <td><?php echo e($master['real_name']); ?></td>
                        </tr>
                        <tr>
                            <td class='text-right '>手机号:</td>
                            <td>
                                <div class='mobile_show'>
                                    <span class="mobile" onlick="return false"><?php echo e($master['mobile']); ?></span>
                                    <a href="javascript:;" class="editPhone" style="margin-left: 10px;">[修改]</a>
                                </div>
                                <div class='mobile_edit' style='display: none;'>
                                    <input type='text' name='mobile' value='' />
                                    <button class='btn btn-primary btn-sm btn_true'>确认</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <button class='btn btn-info btn-sm btn_false'>取消</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class='text-right '>邮箱:</td>
                            <td><?php echo e($master['email']); ?></td>
                        </tr>
                        <tr>
                            <td class='text-right '>角色:</td>
                            <td>
                                <?php $__empty_1 = true; foreach($master_roles as $role): $__empty_1 = false; ?>
                                    <?php echo e($role); ?>

                                <?php endforeach; if ($__empty_1): ?>
                                    无
                                <?php endif; ?>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagejs'); ?>
    <script>
        $(function(){
            var om = 10086;
            var reg = /0?(13|14|15|18|17)[0-9]{9}/;
            toastr.options = {
                "positionClass": "toast-top-right"
            };
            $('.editPhone').click(function(){
                var m = reg.exec($('.mobile').html());
                om = m[0];
                $('input[name=mobile]').val(om);
                edit_show();
            });
            $('.btn_false').click(function(){
                edit_hide();
            });
            $('.btn_true').click(function(){
                var m = $('input[name=mobile]').val();
                if(m == om){
                    toastr.warning('手机号未发生变化！');
                    return false;
                }
                if(isMobile(m)){
                    $.post('/main/editmobile',{mobile:m},function(e){
                        if(e.code == 1){
                            toastr.success(e.msg);
                            $('.mobile').html(m);
                            edit_hide();
                        }else{
                            toastr.warning(e.msg);
                        }
                    },'json');
                }else{
                    toastr.warning('手机号格式错误！');
                }
            });
        });

        function edit_show()
        {
            $('.mobile_show').hide();
            $('.mobile_edit').show();
        }
        function edit_hide()
        {
            $('.mobile_show').show();
            $('.mobile_edit').hide();
        }
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footerjs'); ?>
    <script src="/assets/js/jquery.fancybox.pack.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('home.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>