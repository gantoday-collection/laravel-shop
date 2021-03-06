@extends('admin.layouts.default')

@section('head_css')
<link rel="stylesheet" type="text/css" href="/assets/global/plugins/datatables/datatables.css"/>
<link rel="stylesheet" type="text/css" href="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css"/>

@endsection

@section('content')
<div class="page-content">
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/">首页</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">积分规则</a>
            </li>
        </ul>
    </div>

    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12" >
            <div class="portlet light bordered">

                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li>
                            <a href="/admin/userList" > 会员列表 </a>
                        </li>
                        <li>
                            <a href="/admin/growth" > 会员等级 </a>
                        </li>
                        <li class="active">
                            <a href="javascript:void(0)" > 积分规则 </a>
                        </li>

                        <div style="float:right">
                            <label onclick="GridClickFunction_Add()" class="btn btn-transparent dark btn-outline btn-circle btn-sm">
                                <i class="fa fa-plus"></i> 新增规则</label>
                        </div>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_1_1">
                            <div class="portlet-body table-container">
                                <div class="table-group-search-wrapper" style="display:none">
                                    <div class="input-group input-medium pull-right">
                                        <input type="text" id="keyword" name="keyword" placeholder="关键字" class="keyword form-control" />
                                        <span class="input-group-btn">
                                <button class="btn green searchbutton">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                                    </div>
                                </div>
                                <table id="data_tables" class="table table-striped table-bordered table-hover table-checkable order-column" >
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>规则名称</th>
                                        <th>唯一编码</th>
                                        <th>成长值</th>
                                        <th>有效期</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('foot_script')
<script type="text/javascript" src="/assets/global/plugins/datatables/datatables.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
<script type="text/javascript" src="/assets/global/scripts/datatable.js" ></script>

<script type="text/javascript" >

    var table;

    function RenderOptionCol(val,type,item)
    {
        var opts = '';
        opts += '<a href="javascript:void(0)" class="btn-edit">编辑</a>&nbsp;&nbsp;';
        opts += '<a href="javascript:void(0)" class="btn-delete">删除</a> ';
        return opts;
    }

    $(document).ready(function(){

        var cols = [
            {data:'id',name:'id',orderable:false,createdCell:function(cell, cellData, rowData, rowIndex, colIndex){
                $(cell).html(rowIndex+1)} },
            {data:'name',name:'name',orderable:true,searchable:true,visible:true,render:function(val){
                return val} },
            {data:'code',name:'code',orderable:true,searchable:true,visible:true,render:function(val){
                return val} },
            {data:'growth',name:'growth',orderable:true,searchable:true,render:function(val){
                return val}},
            {data:'start_time',name:'start_time',orderable:false,searchable:true,render:function(val,type,item){
                return val + " ~ " + item.end_time }},
            {data:'status',name:'status',orderable:true,searchable:true,render:function(val){
                if(val==1){
                    return "正常"
                }else{
                    return "禁用"
                }
             }},
            {data:'','name':'',orderable:false,searchable:false,width:'60px',render:RenderOptionCol },
        ];

        var grid = new Datatable();
        grid.init({
            src: $("#data_tables"),
            dataTable: {
                "columns":cols,
                "ajax": {
                    "url": "/admin/growthEvent/query",
                },
                "order": [
                    [1, "asc"]
                ]
            }
        });

        table = grid.getDataTable();
        table.on('click','td',function(e){
            var rowIndex = table.cell(this).index().row;
            var rowData  = table.row(rowIndex).data();
            if($(e.target).is('.btn-edit')){
                e.stopPropagation();
                GridClickFunction_Edit(rowData);
            }else if($(e.target).is('.btn-delete')){
                e.stopPropagation();
                GridClickFunction_Delete(rowData);
            }
        });
    });

    function GridClickFunction_Delete(item){
        WX.Confirm('确定要删除么？',function(){
            var url = "/admin/growthEvent/delete/"+item.id;
            AjaxAction({'url':url, 'method':'GET','success':function(data){
                if(data.code == 1) {
                    WX.toastr({'type':'success','message':'删除成功.','onHidden':function(){
                        table.ajax.reload();
                    }});
                } else {
                    WX.toastr({'type':'error','message':'删除失败.'});
                }
            }});
        });
    }

    function GridClickFunction_Add(){
        location.href = '/admin/growthEvent?act=add';
    }

    function GridClickFunction_Edit(item){
        var url = "/admin/growthEvent?act=edit&id="+item.id;
        location.href = url ;
    }

</script>
@endsection