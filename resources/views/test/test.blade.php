@extends('home.layout')
@section('pagecss')
    <link rel="stylesheet" href="/assets/plugins/select2/select2.min.css">
@endsection
@section('content')
    <img src="" width="100px" id="file_src2">
    <input type="hidden" name="avatar" id="file_name2" value="" /><br />
    <input type="button" value="上传图片" style="width: 100px;" onclick="auto_upload_file('/common/uploadImg','file_src2','file_name2')">
@endsection
@section('pagejs')
    <script type="text/javascript" src="/assets/js/common/auto_upload_file.js"></script>
@endsection
