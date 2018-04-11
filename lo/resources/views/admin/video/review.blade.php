@extends('layout.admin')
@section('content')
<div class="tpl-portlet-components" style="min-height:100vh">
    <div class="tpl-content-page-title">
        首页
    </div>
    <ol class="am-breadcrumb">
        <li><a href="#" class="am-icon-home">首页</a></li>
        <li><a href="#">视频管理</a></li>
        <li class="am-active">视频列表</li>
    </ol>
    <div class="portlet-title">
        <div class="caption font-green bold">
            <span class="am-icon-navicon"></span> 视频列表
        </div>
        
    </div>
    <div class="tpl-block">
        <div class="am-g">
            <div class="am-u-sm-12 am-u-md-6">
                <div class="am-btn-toolbar">
                    <div class="am-btn-group am-btn-group-xs">
                        <a class="am-btn am-btn-default am-btn-success" role="button" href="{{url('/admin/video/create')}}" target="main"><span class="am-icon-plus"></span>发布视频</a>
                    </div>
                </div>
            </div>
            
            
        </div>
        <div class="am-g">
            <div class="am-u-sm-12">
                <form class="am-form">
                    <table class="am-table am-table-striped am-table-hover table-main" >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>视频名称</th>
                                <th>类型</th>
                                <th>vip</th>
                                <th>状态</th>
                                <th>点击量</th>
                                <th>发布时间</th>
                                <th class="table-set">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($arr as $v)
                            <tr>
                                <td>{{$v['id']}}</td>
                                <td>{{$v['video_title']}}</td>
                                <td>{{$v['type']}}</td>
                                @if($v['vip']==1)
                                <td>是</td>
                                @else
                                <td>否</td>
                                @endif
                                @if($v['status']==0)
                                <td>开启</td>
                                @else
                                <td>关闭</td>
                                @endif
                                <td>{{$v['clicks']}}</td>
                                <td>{{date('Y-m-d H:i:s',$v['created_at'])}}</td>
                                <td>
                                    <div class="am-btn-toolbar">
                                        <div class="am-btn-group am-btn-group-xs">
                                            <a class="am-btn am-btn-default am-btn-xs am-text-secondary" onclick="pass({{$v['id']}},$(this))" style="background:#fff"><span class="am-icon-pencil-square-o"></span> 通过</a>
                                            <a class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" onclick="del({{$v['id']}},$(this))" style="background:#fff"><span class="am-icon-trash-o"></span> 删除</a>
                                            <a class="am-btn am-btn-default am-btn-xs am-text-success" href="{{url('/video/play/index/'.$v['id'])}}" style="background:#fff"><span class="am-icon-video-camera" ></span> 查看视频</a>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="am-cf">
                        
                        <div class="am-fr">
                        </div>
                    </div>

                    <hr>

                </form>
            </div>

        </div>
    </div>
    <div class="tpl-alert"></div>
</div>
<script type="text/javascript">
    //审核通过
    function pass(id,obj){
        $.post('/admin/video/pass/'+id,{'_token':"{{csrf_token()}}"},function(data){
            if(data=='0'){
                layer.msg('恭喜，通过审核', {icon: 1});
                obj.parent().parent().parent().parent().remove();
            }else{
                layer.msg('抱歉，未通过审核', {icon: 5});
                return false;
            }
        })
    }
    //执行删除
    function del(id,obj){
        layer.confirm('确定要删除吗？', {
          btn: ['确定','取消'] //按钮
        }, function(){
            $.post('/admin/video/'+id,{'_token':"{{csrf_token()}}",'_method':'delete'},function(data){
                if(data=='0'){
                    layer.msg('恭喜，删除成功', {icon: 1});
                    obj.parent().parent().parent().parent().remove();
                }else{
                    layer.msg('抱歉，删除失败', {icon: 5});
                    return false;
                }
            })
           
        });
        
    }
</script>
@endsection