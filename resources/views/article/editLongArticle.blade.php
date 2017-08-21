@extends('layouts.index')
@section('pagecss')
    <link rel="stylesheet" href="/assets/plugins/select2/select2.min.css">
    <style type="text/css">
        input[type="file"]{display: inline;}
    </style>
    @endsection
    @section('content')
            <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content" style="margin:0 13px 0 13px;">
        <div class="row">
            <form action="" id="dataForm">
                <input type="hidden" value="{{$articleInfo['id']}}" name="articleId" />
                <div class="col-xs-12">
                    <div class="box-body table-responsive">
                        <table class="table table-hover table-bordered table-striped">
                            <tr class="success">
                                <th align="right" width="20%" class="text-center">主标题<span class="text-red">*</span>:</th>
                                <td width="80%"><input type="text" name="title" class="form-control" value="{{$articleInfo['title']}}" /></td>
                            </tr>
                            <tr class="success">
                                <th align="right" width="20%" class="text-center">副标题:</th>
                                <td width="80%"><input type="text" name="subtitle" class="form-control" value="{{$articleInfo['subtitle']}}" /></td>
                            </tr>
                            <tr class="success">
                                <th align="right" width="20%" class="text-center">引言:</th>
                                <td width="80%"><textarea name="introduction" class="form-control" >{{$articleInfo['introduction']}}</textarea></td>
                            </tr>
                            <tr class="success">
                                <th align="right" width="20%" class="text-center">上传封面<span class="text-red">*</span>:</th>
                                <td width="80%">
                                    预 览: <img src="{{\App\Library\Common::getImgHost()}}{{$articlePic['pic_url']}}" width="200px" />
                                    <br /><br />
                                    重新上传: <input type="file" name="article_pic" />
                                    <input type="hidden" value="{{$articlePic['id']}}" name="articlePicId" />
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div class="box-body table-responsive">
                        <input type="button" class="btn btn-xs btn-success" value="增加内容" id="addContentBtn"> (内容可拖动排序)
                    </div>

                    <div class="box-body table-responsive sortable" id="contentDiv">
                        @foreach($contents as $key => $content)
                        <table class="table table-hover table-bordered table-striped">
                            <tr class="success">
                                <th width="10%" align="right" class="text-center" rowspan="3">
                                    <input type="button" class="btn btn-xs btn-danger deleteContentBtn" value="删除内容">
                                </th>
                            </tr>
                            <tr class="success">
                                <td class="text-center" width="20%">
                                    <input type="hidden" name="contentId[]" value="{{$content['id']}}" />
                                    类型:
                                    <select name="contentType[]" class="form-control contentType">
                                        <option value="">请选择类型</option>
                                        <option value="1" @if($content['type'] == '1') selected @endif>标题</option>
                                        <option value="2" @if($content['type'] == '2') selected @endif>图片</option>
                                        <option value="3" @if($content['type'] == '3') selected @endif>正文</option>
                                    </select>
                                </td>
                                <td width="60%">
                                    @if($content['type'] == '1' || $content['type'] == '3')
                                        内容:<textarea name="content[]" class="form-control" >{{$content['content']}}</textarea>
                                        <input type="file" name="content_pic[]" class="hidden" />
                                        <input type="text" name="modified[]" class="hidden" value="1" />
                                    @else
                                        预 览: <img src="{{\App\Library\Common::getImgHost()}}{{$content['pic_url']}}" width="200px" />
                                        <br /><br />
                                        重新上传: <input type="file" name="content_pic[]" />
                                        <input type="text" name="content[]" class="hidden" />
                                        <input type="text" name="modified[]" class="hidden" value="0" />
                                    @endif
                                </td>
                            </tr>
                        </table>
                        @endforeach
                    </div>

                    <div class="box-body table-responsive">
                        <table class="table table-hover table-bordered table-striped">
                            <tr class="success">
                                <th align="right" width="20%" class="text-center">话题标签<span class="text-red">*</span>:</th>
                                <td width="80%">
                                    @foreach($opTags as $tag)
                                        <input type="checkbox" name="tag[]" value="{{$tag['id']}}" @if(in_array($tag['id'],$articleTagIds)) checked @endif  />{{$tag['tag_content']}}
                                    @endforeach
                                </td>
                            </tr>
                            <tr class="success">
                                <th align="right" width="20%" class="text-center">品牌车系车型:</th>
                                <td width="80%">
                                    <select style="width: 150px;" name="brandId" class="select2" id="brandId">
                                        <option value="">请选择品牌</option>
                                        @foreach($brandList as $val)
                                            <option value="{{$val['brandid']}}" @if($val['brandid'] == $articleInfo['car_brand_id']) selected @endif>{{$val['brandname']}}</option>
                                        @endforeach
                                    </select>
                                    <select style="width: 150px;" name="seriesId" class="select2" id="seriesId">
                                        <option value="">请选择车系</option>
                                        @foreach($seriesList as $val)
                                            <option value="{{$val['seriesid']}}" @if($val['seriesid'] == $articleInfo['car_series_id']) selected @endif>{{$val['seriesname']}}</option>
                                        @endforeach
                                    </select>
                                    <select style="width: 300px;" name="modeId" class="select2" id="modeId">
                                        <option value="">请选择车型</option>
                                        @foreach($modeList as $val)
                                            <option value="{{$val['modeid']}}" @if($val['modeid'] == $articleInfo['car_model_id']) selected @endif>{{$val['modename']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr class="success hidden">
                                <th align="right" width="20%" class="text-center">请选择机器人性别<span class="text-red">*</span>:</th>
                                <td width="80%">

                                </td>
                            </tr>
                        </table>
                    </div>

                </div>

                @if($view != 1)
                <div id="btnDiv" class="box-footer clearfix text-center">
                    <input type="button" class="btn btn-success hidden" id="viewBtn" value="生成H5预览">
                    <input type="button" class="btn btn-danger" id="pubBtn" value="保存发布">
                </div>
                @endif
            </form>

            <table class="table table-hover table-bordered table-striped hidden" id="contentTableHide">
                <tr class="success">
                    <th align="right" class="text-center" rowspan="3" width="10%">
                        <button type="button" class="btn btn-xs btn-danger deleteContentBtn">删除内容</button>
                    </th>
                </tr>
                <tr class="success">
                    <td class="text-center" width="20%">
                        类型:
                        <input type="hidden" name="contentId[]" value="0" />
                        <select name="contentType[]" class="form-control contentType">
                            <option value="">请选择类型</option>
                            <option value="1">标题</option>
                            <option value="2">图片</option>
                            <option value="3">正文</option>
                        </select>
                    </td>
                    <td width="60%">&nbsp;</td>
                </tr>
            </table>
        </div>
    </section>
    <!-- /.content -->

@endsection
@section('pagejs')
    <script type="text/javascript" src="/assets/plugins/select2/select2.full.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $(".select2").select2();
            toastr.options = {
                "positionClass": "toast-top-center",
                "preventDuplicates": true
            };
            //发布按钮
            $('#pubBtn').click(function(){
                var _this = $(this);
                _this.val('处理中...');
                var formData = new FormData($('#dataForm')[0]);
                $.ajax({
                    type : 'post',
                    url : '/longArticle/editLongArticle',
                    data : formData,
                    dataType :'json',
                    processData : false,
                    contentType : false,
                    error : function(){
                        toastr.error('提交失败,请重试');
                        _this.val('保存发布');
                    },
                    success : function(response){
                        if(response.code == 1){
                            alert(response.msg);
                            window.parent.$.fancybox.close();
                        }else{
                            toastr.error(response.msg);
                        }
                        _this.val('保存发布');
                    }
                });
                return false;
            });
            //选择品牌
            $('#brandId').change(function(){
                var brandId = $(this).val();
                if(!brandId){
                    return false;
                }
                $.get('/longArticle/getSeriesList',{brandId:brandId},function(response){
                    $('#select2-seriesId-container').attr('title','请选择车系').html('请选择车系');
                    $('#seriesId').html(response);
                });
            });
            //选择车系
            $('#seriesId').change(function(){
                var seriesId = $(this).val();
                if(!seriesId){
                    return false;
                }
                $.get('/longArticle/getModeList',{seriesId:seriesId},function(response){
                    $('#select2-modeId-container').attr('title','请选择车型').html('请选择车型');
                    $('#modeId').html(response);
                });
            });
            //增加内容
            $('#addContentBtn').click(function(){
                var table = $('#contentTableHide').clone().removeClass('hidden').removeAttr('id');
                //table.find('input:not("input[type=\'hidden\']")').val('');
                $('#contentDiv').append(table);
            });
            //删除内容
            $(document).on('click','.deleteContentBtn',function(){
                $(this).parents('table').remove();
            });
            $(document).on('change','.contentType',function(){
                var val = $(this).val();
                if(val == 1 || val == 3){
                    $(this).parent().next().html('内容:<textarea name="content[]" class="form-control" ></textarea> <input type="file" name="content_pic[]" class="hidden" /> <input type="text" name="modified[]" class="hidden" value="1" />');
                }else if(val == 2){
                    $(this).parent().next().html('上传:<input type="file" name="content_pic[]" /> <input type="text" name="content[]" class="form-control hidden" /> <input type="text" name="modified[]" class="hidden" value="1" />');
                }
            });
            //内容拖动排序
            $( ".sortable" ).sortable({
                cursor: "move",
                items : "table",                        //只是li可以拖动
                opacity: 0.6,                       //拖动时，透明度为0.6
                revert: true,                       //释放时，增加动画
                update : function(event, ui){       //更新排序之后
                    //alert($(this).sortable("toArray"));
                }
            });
        });
    </script>
@endsection
