<option value="">请选择车型</option>
@foreach($list as $val)
    <option value="{{$val['modeid']}}">{{$val['modename']}}</option>
@endforeach