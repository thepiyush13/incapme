var products = [
		    {productid:'FI-SW-01',name:'Koi'},
		    {productid:'K9-DL-01',name:'Dalmation'},
		    {productid:'RP-SN-01',name:'Rattlesnake'},
		    {productid:'RP-LI-02',name:'Iguana'},
		    {productid:'FL-DSH-01',name:'Manx'},
		    {productid:'FL-DLH-02',name:'Persian'},
		    {productid:'AV-CB-01',name:'Amazon Parrot'}
		];
		$(function(){
			$('#tt').datagrid({
				title:'Editable DataGrid',
				iconCls:'icon-edit',
				width:1000,
				height:350,
				singleSelect:true,
				idField:'itemid',
				url:'data/datagrid_data.json',
				columns:[[
					{field:'itemid',title:'Item ID',width:130},
					{field:'productid',title:'Product',width:200,
						formatter:function(value){
							for(var i=0; i<products.length; i++){
								if (products[i].productid == value) return products[i].name;
							}
							return value;
						},
						editor:{
							type:'combobox',
							options:{
								valueField:'productid',
								textField:'name',
								data:products,
								required:true
							}
						}
					},
					{field:'listprice',title:'List Price',width:120,align:'right',editor:{type:'numberbox',options:{precision:1}}},
					{field:'unitcost',title:'Unit Cost',width:120,align:'right',editor:'numberbox'},
					{field:'attr1',title:'Attribute',width:200,editor:'text'},
					{field:'status',title:'Status',width:90,align:'center',
						editor:{
							type:'checkbox',
							options:{
								on: 'P',
								off: ''
							}
						}
					},
					{field:'action',title:'Action',width:130,align:'center',
						formatter:function(value,row,index){
							if (row.editing){
								var s = '<a href="#" onclick="saverow(this)">Save</a> ';
								var c = '<a href="#" onclick="cancelrow(this)">Cancel</a>';
								return s+c;
							} else {
								var e = '<a href="#" onclick="editrow(this)">Edit  |</a> ';
								var d = '<a href="#" onclick="deleterow(this)">Delete</a>';
								return e+d;
							}
						}
					}
				]],
				onBeforeEdit:function(index,row){
					row.editing = true;
					updateActions(index);
				},
				onAfterEdit:function(index,row){
					row.editing = false;
					updateActions(index);
				},
				onCancelEdit:function(index,row){
					row.editing = false;
					updateActions(index);
				}
			});
		});
		function updateActions(index){
			$('#tt').datagrid('updateRow',{
				index: index,
				row:{}
			});
		}
		function getRowIndex(target){
			var tr = $(target).closest('tr.datagrid-row');
			return parseInt(tr.attr('datagrid-row-index'));
		}
		function editrow(target){
			$('#tt').datagrid('beginEdit', getRowIndex(target));
		}
		function deleterow(target){
			$.messager.confirm('Confirm','Are you sure?',function(r){
				if (r){
					$('#tt').datagrid('deleteRow', getRowIndex(target));
				}
			});
		}
		function saverow(target){
			$('#tt').datagrid('endEdit', getRowIndex(target));
		}
		function cancelrow(target){
			$('#tt').datagrid('cancelEdit', getRowIndex(target));
		}
		function insert(){
			var row = $('#tt').datagrid('getSelected');
			if (row){
				var index = $('#tt').datagrid('getRowIndex', row);
			} else {
				index = 0;
			}
			$('#tt').datagrid('insertRow', {
				index: index,
				row:{
					status:'P'
				}
			});
			$('#tt').datagrid('selectRow',index);
			$('#tt').datagrid('beginEdit',index);
		}