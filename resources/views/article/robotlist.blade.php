@extends('layouts.index')
@section('pagecss')
<link rel="stylesheet" href="/assets/css/jquery.fancybox.css">
<link rel="stylesheet" href="/assets/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css">
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<small></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> 主页</a></li>
		<li><a href="#">大斑马</a></li>
		<li class="active">图集管理</li>
	</ol>
</section>
<!-- Main content -->
<section class="content" style="margin:0 15px 0 15px;">
	<div class="row">
		<!-- Custom Tabs -->
		<div class="nav-tabs-custom">
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<div class="row">
						<div class="col-xs-12">
							<div class="box">
								<!-- /.box-header -->
								<div class="box-body table-responsive no-padding">
									<table class="table table-hover table-bordered table-striped">
										<tr class="text-nowrap">
											<th> 选择</th>
											<th> 昵称</th>
											<th> 性别</th>
											<th> 城市</th>
											<th> 年龄</th>
											<th> 话题分类</th>
											<th> 职业</th>
											<th> 秀维度</th>
											<th> 秀数量</th>
										</tr>
										@if(isset($result))
										@foreach($result as $temp)
										<tr>
											<td><input type="radio" name="user_id" value="{{$temp['user_id']}}" @if(isset($user_id)&&($temp['user_id'] == $user_id)) checked @endif></td>
											<td>{{$temp->nickname}}</td>
											<td>@if($temp->gender == 1) 男 @else 女 @endif</td>
											<td>@if($temp->city){{$temp->city}}@endif</td>
											<td>@if($temp->age > 0){{$temp->age}}@endif</td>
											<td>
												<?php $inter = explode(",",$temp->interests) ?>
												@if(isset($inter))
													@foreach($inter as $tmp)
														@if(isset($interest[$tmp]))
															{{$interest[$tmp]}}&nbsp;
														@endif
													@endforeach
												@endif
											</td>
											<td>@if(isset($temp->career)){{$temp->career}}@endif</td>
											<td>@if(isset($temp->show_range)){{$temp->show_range}}@endif</td>
											<td>@if(isset($temp->user_id)&&isset($num[$temp->user_id])){{$num[$temp->user_id]}}@endif</td>
										</tr>
										@endforeach
										@endif
									</table>
								</div>
								<div class="box-footer clearfix text-center">
			                        <input id="save_send" type="button" class="btn btn-warning dropdown-toggle" value="确定">
			                        <input id="closeBtn" type="button" class="btn btn-danger" value="取消">
			                    </div>
								<!-- /.box-body -->
								<div class="box-footer clearfix">
									<!--分页-->
									{!! $result->appends($params)->render() !!}
								</div>
							</div>
							<!-- /.box -->
						</div>
					</div>
				</div>
				<!-- /.tab-pane -->
				<div class="tab-pane" id="tab_2">
				</div>
				<!-- /.tab-pane -->
			</div>
			<!-- /.tab-content -->
		</div>
		<!-- nav-tabs-custom -->
	</div>
</section>
<!-- /.content -->
@endsection
@section('pagejs')
<script type="text/javascript" src="/assets/js/jquery.fancybox.pack.js"></script>
<script src="/assets/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="/assets/plugins/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript">
	$(function(){
		$("#save_send").on('click',function(e){
			if($("input[name='user_id']:checked").length == 0){
                alert('请选择要使用的大V！');
                return false;
            }
            var user_id = $("input[name='user_id']:checked").val();
            parent.$('#v_userid').val(user_id);
			parent.$.fancybox.close();
            return false;
		})
		//关闭
		$("#closeBtn").on('click',function(e){
            window.parent.$.fancybox.close();
        })
	})
</script>
@endsection