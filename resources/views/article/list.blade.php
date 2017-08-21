@extends('home.layout')
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
            <li><a href="#">内容管理</a></li>
            <li class="active">文章列表</li>
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
                            <div class="col-md-12">
                                <!-- Form -->
                                <div class="box box-default" style="padding:12px;">
                                    <!-- form start -->
                                    <form class="form-inline" action="" method="get" id="searchForm">
                                        <div class="form-group">
                                            <label for="id">ID:</label>
                                            <input type="text" class="form-control" id="id" name="id" placeholder="" value="@if(isset($params['id'])){{$params['id']}}@endif" >
                                        </div>
                                        <div class="form-group">
                                            <label for="title">标题:</label>
                                            <input type="text" class="form-control" id="title" name="title" placeholder="" value="@if(isset($params['title'])){{$params['title']}}@endif" >
                                        </div>
                                        <div class="form-group">
                                            <label for="start_time">发布时间:</label>
                                            <input type="text" class="form-control" id="start_time" name="start_time" value="{{$params['start_time'] or ''}}" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="end_time">至</label>
                                            <input type="text" class="form-control" id="end_time" name="end_time" value="{{$params['end_time'] or ''}}" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="type">类型:</label>
                                            <select id="type"  name="type" class="form-control">
                                                <option value="0">全部</option>
                                                <option value="1" @if(isset($params['type'])&&$params['type']==1) selected @endif>专业博文</option>
                                                <option value="2" @if(isset($params['type'])&&$params['type']==2) selected @endif>外部摘录</option>
                                                <option value="3" @if(isset($params['type'])&&$params['type']==3) selected @endif>心得随笔</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="status">状态:</label>
                                            <select id="status"  name="status" class="form-control">
                                                <option value="0">全部</option>
                                                <option value="1" @if(isset($params['status'])&&$params['status']==1) selected @endif>未审核</option>
                                                <option value="2" @if(isset($params['status'])&&$params['status']==2) selected @endif>审核不通过</option>
                                                <option value="3" @if(isset($params['status'])&&$params['status']==3) selected @endif>审核通过</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-danger">查询</button>
                                        <a type="button" href="/article/add" class="fancybox fancybox.iframe btn btn-success">新增文章</a>
                                    </form>
                                </div>
                                <!-- /.box -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title">共{{$lists->total()}}条</h3><br >
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body table-responsive no-padding">
                                        <table class="table table-hover table-bordered table-striped">
                                            <tr class="text-nowrap">
                                                <th> ID </th>
                                                <th> 封面 </th>
                                                <th> 文章标题 </th>
                                                <th> 文章简介 </th>
                                                <th> 发布时间 </th>
                                                <th> 更新时间 </th>
                                                <th> 文章类型 </th>
                                                <th> 状态 </th>
                                                <th> 操作 </th>
                                            </tr>
                                            @foreach ($lists as $article)
                                                <tr class="text-nowrap">
                                                    <td>{{$article->id}}</td>
                                                    <td><img src="{{$article->pic_url_show}}" width="40"></td>
                                                    <td title="{{$article->title}}">{{$article->title_short}}</td>
                                                    <td title="{{$article->introduction}}">{{$article->introduction_short}}</td>
                                                    <td>{{$article->created_at}}</td>
                                                    <td>{{$article->updated_at}}</td>
                                                    <td>{{$article->type_show}}</td>
                                                    <td>{{$article->status_show}}</td>
                                                    <td>
                                                        <a href="/article/edit?id={{$article->id}}" class="fancybox fancybox.iframe btn btn-xs btn-success">编辑</a>
                                                        <a href="/article/delete?id={{$article->id}}" class="btn btn-xs btn-danger deleteArticle">删除</a>
                                                        @if($article->status == 1 || $article->status == 2)
                                                            <a href="/article/audit?id={{$article->id}}&opt=1" class="btn btn-xs btn-success auditArticle">通过</a>
                                                        @endif
                                                        @if($article->status == 1 || $article->status == 3)
                                                            <a href="/article/audit?id={{$article->id}}&opt=2" class="btn btn-xs btn-success auditArticle">不通过</a>
                                                        @endif

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <!-- /.box-body -->
                                    <div class="box-footer clearfix">
                                        {!! $lists->appends($params)->render() !!}
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
        $(function () {
            $('#start_time,#end_time').datetimepicker({
                minView: "month",
                format: "yyyy-mm-dd",
                language: 'zh-CN',
                autoclose:true
            });
            $(".fancybox").fancybox({width:'100%'});
            bootbox.setLocale('zh_CN');
            toastr.options = {
                "positionClass": "toast-top-center",
                "preventDuplicates": true
            };
            $('.deleteArticle').click(function(){
                var href = $(this).attr('href');
                bootbox.confirm({
                    message: '确定要删除吗?',
                    callback: function(result){
                        if(result){
                            $.get(href,function(response){
                                alert(response.msg);
                                location.reload();
                            },'json');
                        }
                    }
                });
                return false;
            });
            $('.auditArticle').click(function(){
                var href = $(this).attr('href');
                $.get(href,function(response){
                    alert(response.msg);
                    location.reload();
                },'json');
                return false;
            });
        });
    </script>
@endsection