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
            <li><a href="#"><i class="fa fa-dashboard"></i>Wiki列表</a></li>
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
                                            <label for="project">项目名称:</label>
                                            <input type="text" class="form-control" id="project" name="project" placeholder="" value="@if(isset($params['project'])){{$params['project']}}@endif" >
                                        </div>
                                        <div class="form-group">
                                            <label for="modular">模块名称:</label>
                                            <input type="text" class="form-control" id="modular" name="modular" placeholder="" value="@if(isset($params['modular'])){{$params['modular']}}@endif" >
                                        </div>
                                        <div class="form-group">
                                            <label for="name">接口名称:</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="" value="@if(isset($params['name'])){{$params['name']}}@endif" >
                                        </div>
                                        <div class="form-group">
                                            <label for="url">接口地址:</label>
                                            <input type="text" class="form-control" id="url" name="url" placeholder="" value="@if(isset($params['url'])){{$params['url']}}@endif" >
                                        </div>
                                        <div class="form-group">
                                            <label for="order_type">排序类型:</label>
                                            <select id="order_type"  name="order_type" class="form-control">
                                                <option value="0">按项目模块排序</option>
                                                <option value="1" @if(isset($params['order_type'])&&$params['order_type']==1) selected @endif>按创建时间倒序</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-danger">查询</button>
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
                                                <th> 项目名称 </th>
                                                <th> 模块名称 </th>
                                                <th> 接口名称 </th>
                                                <th> 接口地址 </th>
                                                <th> 创建时间 </th>
                                                <th> 更新时间 </th>
                                                <th> 操作 </th>
                                            </tr>
                                            @foreach ($lists as $article)
                                                <tr class="text-nowrap">
                                                    <td>{{$article->id}}</td>
                                                    <td>{{$article->project}}</td>
                                                    <td>{{$article->modular}}</td>
                                                    <td>{{$article->name}}</td>
                                                    <td>{{$article->url}}</td>
                                                    <td>{{$article->created_at}}</td>
                                                    <td>{{$article->updated_at}}</td>
                                                    <td>
                                                        <a href="/wiki/data?id={{$article->id}}" class="fancybox fancybox.iframe btn btn-xs btn-success">查看</a>
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
            $(".fancybox").fancybox({width:'100%'});
            bootbox.setLocale('zh_CN');
            toastr.options = {
                "positionClass": "toast-top-center",
                "preventDuplicates": true
            };
        });
    </script>
@endsection