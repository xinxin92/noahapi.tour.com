@extends('home.layout')
@section('pagecss')
    <link rel="stylesheet" href="/assets/plugins/select2/select2.min.css">
@endsection
@section('content')
    <!-- Main content -->
    <section class="content" style="margin:0 13px 0 13px;">
        <div class="row">
            <form action="" id="dataForm">
                <div class="col-xs-12">
                    <div class="box-body table-responsive">
                        <table class="table table-hover table-bordered table-striped">
                            <tr class="success">
                                <th align="right" width="10%" class="text-center">接口名称:</th>
                                <td width="90%"><input type="text" readonly="true" name="url" class="form-control" value="{{$data['project']}}-{{$data['modular']}}-{{$data['name']}}" /></td>
                            </tr>
                            <tr class="success">
                                <th align="right" width="10%" class="text-center">接口地址:</th>
                                <td width="90%"><input type="text" readonly="true" name="url" class="form-control" value="{{$data['url']}}" /></td>
                            </tr>
                            <tr class="success">
                                <th align="right" width="10%" class="text-center">请求方式:</th>
                                <td width="90%"><input type="text" readonly="true" name="method" class="form-control" value="{{$data['method']}}" /></td>
                            </tr>
                            <tr class="success">
                                <th align="right" width="10%" class="text-center">请求参数:</th>
                                <td width="90%"><textarea readonly="true" rows="5" name="request" class="form-control" >{{$data['request']}}</textarea></td>
                            </tr>
                            <tr class="success">
                                <th align="right" width="10%" class="text-center">返回结果:</th>
                                <td width="90%"><textarea readonly="true" rows="20" name="response" class="form-control" >{{$data['response']}}</textarea></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
@endsection
