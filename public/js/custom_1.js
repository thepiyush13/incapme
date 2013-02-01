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
			$('#participant_details').datagrid({
				title:'Edit Activity',
				iconCls:'icon-edit',
                width:1000,
				height:350,
				singleSelect:true,
				idField:'itemid',
				url:'data/datagrid_data.json',
				columns:[[
					{field:'itemid',title:'Item ID',width:130},
					{field:'productid',title:'Product',width:180,
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
					{field:'listprice',title:'List Price',width:120,align:'center',editor:{type:'numberbox',options:{precision:1}}},
					{field:'unitcost',title:'Unit Cost',width:120,align:'center',editor:'numberbox'},
					{field:'attr1',title:'Attribute',width:194,editor:'text'},
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
			$('#participant_details').datagrid('updateRow',{
				index: index,
				row:{}
			});
		}
		function getRowIndex(target){
			var tr = $(target).closest('tr.datagrid-row');
			return parseInt(tr.attr('datagrid-row-index'));
		}
		function editrow(target){
			$('#participant_details').datagrid('beginEdit', getRowIndex(target));
		}
		function deleterow(target){
			$.messager.confirm('Confirm','Are you sure?',function(r){
				if (r){
					$('#participant_details').datagrid('deleteRow', getRowIndex(target));
				}
			});
		}
		function saverow(target){
			$('#participant_details').datagrid('endEdit', getRowIndex(target));
		}
		function cancelrow(target){
			$('#participant_details').datagrid('cancelEdit', getRowIndex(target));
		}
		function insert(){
			var row = $('#participant_details').datagrid('getSelected');
			if (row){
				var index = $('#participant_details').datagrid('getRowIndex', row);
			} else {
				index = 0;
			}
			$('#participant_details').datagrid('insertRow', {
				index: index,
				row:{
					status:'P'
				}
			});
			$('#participant_details').datagrid('selectRow',index);
			$('#participant_details').datagrid('beginEdit',index);
		}
                
 function validate(){
 
        if( document.loginForm.username.value == "" )
           {
             alert( "Please provide User name!" );
             document.loginForm.username.focus() ;
             return false;
           }
           if( document.loginForm.user-pwd.value == "" )
           {
             alert( "Please Provide Password" );
             document.loginForm.user-pwd.focus() ;
             return false;
           }

               return (true);
           }
           
   function emailvalidate(){
            var x=document.forms["forgotpassword"]["forgotpwd"].value;
            var atpos=x.indexOf("@");
            var dotpos=x.lastIndexOf(".");
             if( document.forgotpassword.forgotpwd.value == "" )
               {
                 alert( "Please Provide Email Id!" );
                 document.forgotpassword.forgotpwd.focus() ;
                 return false;
               }
            if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
              {
              alert("Not a valid e-mail address");
               document.forgotpassword.forgotpwd.focus() ;
              return false;
              }
              alert("Successfully recovered the Password")
               return( true );
            }
			
			
		$("select").change(function () {
			  var str = "";
				str= jQuery("#activity-type select option:selected").text();
				  
					switch(str) {
						 case "Conference": case "Competition": case "National Platform": case "Webinar": case "Review": case "Outreach": case "Startup Support": case "Course": case "Workshop": case "Mentoring": $("#course-credit").show(); $("#activity-duration").show(); break;
						 
						default:
						$("#course-credit").hide();
						$("#activity-duration").hide();
						
					}	
				
			})
			.change();