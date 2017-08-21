<option value="">请选择车系</option>
@foreach($list as $val)
    <option value="{{$val['seriesid']}}">{{$val['seriesname']}}</option>
@endforeach