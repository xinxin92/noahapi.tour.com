/**
 * Created by yuxinwei on 2016/8/25.
 * 调用即触发文件上传，指定后端上传处理程序receive_file接收并处理上传的文件uploadFile(便于自定义各种需求)，若上传成功，则ID为file_src和file_name的元素加载新图信息；
 * 调用的html示例（show/editRobot.blade.php）如下：
 * <tr>
 <td>头像：</td>
 <td>
 <img src="{{$list_avatar}}" width="100px" id="file_src" form="form1">
 <input type="hidden" name="avatar" id="file_name" value="{{$info[0]['avatar']}}" form="form1"/><br />
 <input type="button" value="上传头像" style="width: 100px;" onclick="auto_upload_file('/common/uploadImg')">
 </td>
 </tr>
 * 程序中要含有<input type="hidden" id="token" name="_token" value="<?php echo csrf_token();?>"/>
 */

function auto_upload_file(receive_file,file_src_id,file_name_id) {
    //创建iframe
    var fileIframe = document.createElement("iframe");
    fileIframe.id = 'uploadIframe';
    fileIframe.name = 'uploadIframe';
    fileIframe.style.display = "none";
    document.body.appendChild(fileIframe);
    //创建form表单
    var fileForm = document.createElement("form");
    document.body.appendChild(fileForm);
    fileForm.id = 'uploadForm';
    fileForm.name = 'uploadForm';
    fileForm.method = 'post';
    fileForm.action = receive_file;
    fileForm.target = 'uploadIframe';
    fileForm.enctype = 'multipart/form-data';
    fileForm.style.display='none';
    //创建file表单元素,提交FILE文件
    var fileElement = document.createElement("input");
    fileElement.setAttribute("type","file");
    fileElement.setAttribute("id","uploadFile");
    fileElement.setAttribute("name","uploadFile");
    fileForm.appendChild(fileElement);
    //创建call_back表单元素，用于指定回调函数
    var callBackNameElement = document.createElement("input");
    callBackNameElement.setAttribute("type","text");
    callBackNameElement.setAttribute("name","callBackName");
    callBackNameElement.setAttribute("value",'auto_upload_file_call_back');
    fileForm.appendChild(callBackNameElement);
    //创建file_src表单元素，用于回调时指定要操作的元素id值
    var fileSrcIdElement = document.createElement("input");
    fileSrcIdElement.setAttribute("type","text");
    fileSrcIdElement.setAttribute("name","fileSrcId");
    fileSrcIdElement.setAttribute("value",file_src_id);
    fileForm.appendChild(fileSrcIdElement);
    //创建file_name表单元素，用于回调时指定要操作的元素id值
    var fileNameIdElement = document.createElement("input");
    fileNameIdElement.setAttribute("type","text");
    fileNameIdElement.setAttribute("name","fileNameId");
    fileNameIdElement.setAttribute("value",file_name_id);
    fileForm.appendChild(fileNameIdElement);
    //附带token验证(程序中需要写<input type="hidden" id="token" name="_token" value="<?php echo csrf_token();?>"/>)
    // var token = document.getElementById("token").value;
    // var tokenElement = document.createElement("input");
    // tokenElement.setAttribute("id","token");
    // tokenElement.setAttribute("name","_token");
    // tokenElement.setAttribute("type","hidden");
    // tokenElement.setAttribute("value",token);
    // fileForm.appendChild(tokenElement);
    //自动点击file元素弹出图片选择框
    fileElement.click();
    //选定新图片后提交表单
    fileElement.onchange = function() {
        fileForm.submit();
    };
}
//回调函数：上传成功则加载新图，否则给出提示
function auto_upload_file_call_back(code, msg, file_src, file_name, file_src_id, file_name_id){
    if ( code == 1 ){
        $('#' + file_src_id).attr('src',file_src);
        $('#' + file_name_id).attr('value',file_name);
    } else {
        toastr.error(msg);
    }
    //移除在页面动态创建的iframe和form
    document.body.removeChild(document.getElementById("uploadIframe"));
    document.body.removeChild(document.getElementById("uploadForm"));
}