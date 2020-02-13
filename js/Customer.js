$("#SearchSubmit").click(function(){ //ค้นหาประวัติลูกค้า
    Search_History();
});
var OBJSearchhistory = null;
function Search_History(){
    if(document.getElementById("SearchInput").value != ""){
    dataToPost = new Object();
    dataToPost.SearchInput = $("#SearchInput").val();
    OBJSearchhistory = $.ajax({
        url: '/Customer_History/index.php/Welcome/Search_Show_QueryDB',
        data: dataToPost,
        type: 'POST',
        dataType: 'json',
        success: function(data){
            if(!data.error){
                $('#Showtable_SearchDB').html(data);
				actiontable();
            }
			OBJSearchhistory = null;
        },
		beforeSend:function (){
			if(OBJSearchhistory !== null){
				OBJSearchhistory.abort();
			}
		}
    });
    }else{
         Lobibox.alert('warning', {
            msg:"กรุณากรอกคำที่ต้องการค้นหาครับ"
        });
        $("#Showtable_SearchDB").empty();
    }
}
function actiontable(){
	var KBEditHistorypersons = null;
	$(".EditHistoryCustomer").click(function(){
		Edit_History_Customer($(this).attr('ID_CUSCOD'));
	});	
	var AddressPersons = null;
	$(".ShowAddressDB").click(function(){
		ShowAddressDB($(this).attr('CUSCOD'));
	});
}

function Edit_History_Customer(ID_CUSCOD){
	dataToPost = new Object();
	dataToPost.ID_CUSCOD = ID_CUSCOD;
	KBEditHistorypersons = $.ajax({
		url: '/Customer_History/index.php/Welcome/Edit_History_Customer', 
		data: dataToPost,
		type: 'POST',
		dataType: 'json',
		success: function(data){
			Lobibox.window({
			title:'Form EDIT CUSTOMER',
			width: $(window).width(),                
			height: $(window).height(),
			content: data.html,
				shown: function($window){
					fn_reactive(); //การกระทำในฟอร์มLobiwindow กรอกประวัติลูกค้า
					fn_reactive_addr(); //แก้ไขที่อยู่ในตาราง---buttontable
					Update_Customer_DB($window);
				}
			});
		}
	});
}

function ShowAddressDB(CUSCOD){
	dataToPost = new Object();
	dataToPost.CUSCOD = CUSCOD; 
	AddressPersons = $.ajax({
		url: '/Customer_History/index.php/Welcome/Show_Address_TableDB',
		data: dataToPost,
		type: 'POST',
		dataType: 'json',
		success: function(data){
			Lobibox.window({
				title: 'แสดงที่อยู่',
				content:data.html,
				width: 1100,
				height: 300,
				shown:function($this){
					//actionWindow($this);   
				}
			});
		}
	});
}

function Update_Customer_DB($window){
	$("#SaveUpdateCustomerDB").click(function(){ //UpdateCustomerDB($window);
		UpdateCustomerDB($window);
	});
	$('#CancelUpdate').click(function () {
		Lobibox.confirm({
			msg: "คุณแน่ใจว่าต้องการยกเลิอกการกระทำทั้งหมด?",
			callback: function ($this, type) {
				if (type === 'yes') {
					$window.destroy();
				}
			}
		});
	});
}
$("#AddEmployeeCustomer").unbind("clikck");
$("#AddEmployeeCustomer").click(function(){ //ฟอร์มกรอกประวัติลูกค้า_เพิ่มประวัติลูกค้า
	dataToPost = new Object();
    $.ajax({
		url:'/Customer_History/index.php/Welcome/Form_Add_Employee',
		data:dataToPost,
		type:'POST',
		dataType:'json',
		success: function(data){
			Lobibox.window({
				title:'Form ADD EMPLOYEE',
				width: $(window).width(),                
				height: $(window).height(),
				content: data.html,
				shown: function($window){
					fn_reactive($window); //การกระทำในฟอร์มLobiwindow กรอกประวัติลูกค้า
				}
            });
		}
    });
});

function fn_reactive($window){ //การกระทำในฟอร์มLobiwindow กรอกประวัติลูกค้า
	$('#GROUP1').select2({
		placeholder: 'เลือก',
		ajax: {
			url: '/index.php/Welcome/GROUP1_Select2',
			data: function (params) {
				dataToPost = new Object();
				dataToPost.now  = (typeof $('#GROUP1').find(':selected').val() === 'undefined' ? "":$('#GROUP1').find(':selected').val()); 
				dataToPost.q    = (typeof params.term === 'undefined' ? '' : params.term);

				return dataToPost;    
			},
			dataType: 'json',
			delay: 1000,
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		},
		allowClear: true,
		multiple: false,
		dropdownParent: $('#GROUP1').parent().parent(),
		//disabled: true,
		//theme: 'classic',
		width: '100%'
	});
	
	$('#GRADE').select2({
		placeholder: 'เลือก',
		ajax: {
			url: '/Customer_History/index.php/Welcome/GRADE_Select2',
			data: function (params) {
				dataToPost = new Object();
				dataToPost.now  = (typeof $('#GRADE').find(':selected').val() === 'undefined' ? "":$('#GRADE').find(':selected').val()); 
				dataToPost.q    = (typeof params.term === 'undefined' ? '' : params.term);

				return dataToPost;    
			},
			dataType: 'json',
			delay: 1000,
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		},
		allowClear: true,
		multiple: false,
		dropdownParent: $('#GRADE').parent().parent(),
		//disabled: true,
		//theme: 'classic',
		width: '100%'
	});
	
	$('#SNAM').select2({
		placeholder: 'เลือก',
		ajax: {
			url: '/Customer_History/index.php/Welcome/SNAM_Select2',
			data: function (params) {
				dataToPost = new Object();
				dataToPost.now  = (typeof $('#SNAM').find(':selected').val() === 'undefined' ? "":$('#SNAM').find(':selected').val()); 
				dataToPost.q    = (typeof params.term === 'undefined' ? '' : params.term);

				return dataToPost;    
			},
			dataType: 'json',
			delay: 1000,
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		},
		allowClear: true,
		multiple: false,
		dropdownParent: $('#SNAM').parent().parent(),
		//disabled: true,
		//theme: 'classic',
		width: '100%'
	});
    
    $("#addrno1").select2({
        placeholder: 'เลิอก',		
        minimumResultsForSearch: -1,
        dropdownParent: $("#addrno1").parent().parent(),
        width: '100%'
    });
    $("#addrno2").select2({
        placeholder: 'เลิอก',		
        minimumResultsForSearch: -1,
        dropdownParent: $("#addrno2").parent().parent(),
        width: '100%'
    });
    $("#addrno3").select2({
        placeholder: 'เลิอก',		
        minimumResultsForSearch: -1,
        dropdownParent: $('#addrno3').parent().parent(),
        width: '100%'
    });
    $("#IDCARD").select2({
        placeholder: 'เลือก',
        minimumResultsForSearch: -1,
        dropdownParent: $("#IDCARD").parent().parent(),
        width: '100%'
    });
    $("#MREVENU").select2({
        placeholder: 'เลือก',
        minimumResultsForSearch: -1,
        dropdownParent: $('#MREVENU').parent().parent(),
        width: '100%'
    });
    $("#YREVENU").select2({
        placeholder:'เลือก',
        minimumResultsForSearch: -1,
        dropdownParent: $("#YREVENU").parent().parent(),
        width: '100%'
    });
	//ปฏฺิทินวันที่
		$(document).ready(function () { 
		$("#BIRTHDT").datepicker({
			dateFormat: "dd-mm-yy",
			monthNames: ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
			dayNamesMin: ["อา", "จ", "อ", "พ", "พฤ", "ศ", "ส"],
			todayBtn: true,
			language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
			thaiyear: true              //Set เป็นปี พ.ศ.
		}).datepicker();                //กำหนดเป็นวันปัจุบัน
		$("#ISSUDT").datepicker({
			dateFormat: "dd-mm-yy",
			monthNames: ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
			dayNamesMin: ["อา", "จ", "อ", "พ", "พฤ", "ศ", "ส"],
			todayBtn: true,
			language: 'th',             
			thaiyear: true              
		}).datepicker();  
		$("#EXPDT").datepicker({
			dateFormat: "dd-mm-yy",
			monthNames: ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
			dayNamesMin: ["อา", "จ", "อ", "พ", "พฤ", "ศ", "ส"],
			todayBtn: true,
			language: 'th',
			thaiyear: true              
		}).datepicker();  
	});
	
	var OBJAddAddrFirst = null;
    $("#btnAddAddressFirst").click(function(){
        fn_loadFromADDR('add',null); //btn ฟอร์มกรอกที่อยู่ลูกค้าใหม่
    });
	$("#Cancelcustomer").click(function(){
		Lobibox.confirm({
			msg: "คุณแน่ใจว่าต้องการยกเลิอกการกระทำทั้งหมด?",
			callback: function ($this, type) {
				if (type === 'yes') {
					$window.destroy();
				}
			}
		});
	});
	$("#SaveCustomerDB").click(function(){
		SaveCustomerDB($window);
	});
}
var OBJeditaddress = null;
function fn_loadFromADDR($action,$this){ 
	if($action == "edit"){
		dataToPost = new Object();
		dataToPost.ADDRNO 	= $this.attr("ADDRNO");
		dataToPost.ADDR1 	= $this.attr("ADDR1");
		dataToPost.SOI      = $this.attr("SOI");
		dataToPost.ADDR2 	= $this.attr("ADDR2");
		dataToPost.MOOBAN 	= $this.attr("MOOBAN");
		dataToPost.TUMB 	= $this.attr("TUMB");
		dataToPost.AUMPCOD 	= $this.attr("AUMPCOD");
		dataToPost.PROVCOD 	= $this.attr("PROVCOD");
		dataToPost.AUMPDES 	= $this.attr("AUMPDES");
		dataToPost.PROVDES 	= $this.attr("PROVDES");
		dataToPost.ZIP      = $this.attr("ZIP");
		dataToPost.TELP 	= $this.attr("TELP");
		dataToPost.MEMO1 	= $this.attr("MEMO1");	
	}else{
		dataToPost = new Object();
	}
	dataToPost.ACTION = $action;
	OBJeditaddress = $.ajax({
		url: '/Customer_History/index.php/Welcome/Form_Address_Customer', 
		data: dataToPost,
		type: 'POST',
		dataType: 'json',
		success: function(data){
			Lobibox.window({
				title: 'ฟอร์มที่อยู่ลูกค้า',
				content:data.html, 
				closeButton: false,
				shown:function($thisWindow){
					actionWindow($thisWindow,$action,$this); //การกระทำในฟอร์มLobiwindow เพิ่มที่อยู่ลูกค้าแสดงในตาราง html สามารถเพิ่ม ลบ แก้ไข ก่อนบันทึกเข้าสู่ฐานข้อมูล
					
				},
				beforeClose : function(){
					if($action == "edit"){
						CloseLobiwindow($this,"cancel");
					}
				}
			});
			
			OBJeditaddress = null;
		},
		beforeSend: function(){
			if(OBJeditaddress !== null){
				OBJeditaddress.abort();
			}
		}
	});	
}

/*-------------------------------------------การกระทำในฟอร์มLobiwindow ------------------------------------------------------------------*/
function actionWindow ($window,$action,$this){ //btn การกระทำในฟอร์มLobiwindow เพิ่มที่อยู่ของลูกค้าในแต่ละคน
	var OBJbtnWAClose = null;			
	$("#btnWAClose").unbind('click');
	$("#btnWAClose").click(function(){
		if($action == "edit"){	//แก้ไข ดึงข้อมูลจากตาราง html 
			dataToPost = new Object();
			dataToPost.ADDRNO 	= $this.attr("ADDRNO");
			dataToPost.ADDR1 	= $this.attr("ADDR1");
			dataToPost.SOI     	= $this.attr("SOI");
			dataToPost.ADDR2 	= $this.attr("ADDR2");
			dataToPost.MOOBAN 	= $this.attr("MOOBAN");
			dataToPost.TUMB 	= $this.attr("TUMB");
			dataToPost.AUMPCOD 	= $this.attr("AUMPCOD");
			dataToPost.PROVCOD 	= $this.attr("PROVCOD");
			dataToPost.AUMPDES 	= $this.attr("AUMPDES");
			dataToPost.PROVDES 	= $this.attr("PROVDES");
			dataToPost.ZIP      = $this.attr("ZIP");
			dataToPost.TELP 	= $this.attr("TELP");
			dataToPost.MEMO1 	= $this.attr("MEMO1");
			OBJbtnWAClose = $.ajax({
				url: '/Customer_History/index.php/Welcome/SetAddr_Html',			
				data: dataToPost,
				type: 'POST',
				dataType: 'json',
				success: function(data){
					if(!checkEmptyInputaddress()){
						$("#dataAddress tbody").append(data.Tboby);

						fn_address("edit");
						OBJbtnWAClose = null;
						$window.destroy();
					}
				},
				beforeSend: function(){
					if(OBJbtnWAClose !== null){
						OBJbtnWAClose.abort();
					}
				}
			});
		}
	});
	
	$("#btnWACloseAdd").click(function(){
		$window.destroy();
	});
	
	//เพิ่ม
	var OBJbtnAddAddr = null;	
    $("#btnAddAddrTableHtml").click(function(){		//เพิ่มที่อยู่ของลูกค้าในแต่ละคน 	Lobibo.window
		//alert($(this).attr('KORN'));
        dataToPost = new Object();
        dataToPost.CUSCOD   = $("#CUSCOD").val();
        dataToPost.ADDRNO 	= $("#ADDRNO").val();
        dataToPost.ADDR1 	= $("#ADDR1").val();
        dataToPost.SOI      = $("#SOI").val();
        dataToPost.ADDR2 	= $("#ADDR2").val();
        dataToPost.MOOBAN 	= $("#MOOBAN").val();
        dataToPost.TUMB 	= $("#TUMB").val();
        dataToPost.AUMPCOD 	= (typeof $("#AUMPDES").find(":selected").val()     === "undefined" ? "": $("#AUMPDES").find(":selected").val());
        dataToPost.PROVCOD 	= (typeof $("#PROVDES").find(":selected").val()     === "undefined" ? "": $("#PROVDES").find(":selected").val());
        dataToPost.AUMPDES 	= (typeof $("#AUMPDES").find(":selected").text()    === "undefined" ? "": $("#AUMPDES").find(":selected").text());
        dataToPost.PROVDES 	= (typeof $("#PROVDES").find(":selected").text()    === "undefined" ? "": $("#PROVDES").find(":selected").text());
        dataToPost.ZIP      = (typeof $("#ZIP").find(":selected").val()         === "undefined" ? "": $("#ZIP").find(":selected").val());
        dataToPost.TELP 	= $("#TELP").val();
        dataToPost.MEMO1 	= $("#MEMO1").val();

        OBJbtnAddAddr = $.ajax({
            url: '/Customer_History/index.php/Welcome/SetAddr_Html',			
            data: dataToPost,
            type: 'POST',
            dataType: 'json',
            success: function(data){
                if(!checkEmptyInputaddress()){
                    $("#dataAddress tbody").append(data.Tboby);

                    fn_address("add");
					fn_reactive_addr(); // แก้ไขข้อมูลที่อยู่พนักงาน กรณีเพิ่มที่อยู่ใหม่
					
                    OBJbtnAddAddr = null;
                    $window.destroy();
					
                }     
            },
            beforeSend: function(){
                if(OBJbtnAddAddr !== null){
                    OBJbtnAddAddr.abort();
                }
            }
        });
    });
	//แก้ไข
    var OBJbtneditAdrr = null;
    $("#btneditAdrrTableHtml").click(function(){
        dataToPost = new Object();
        dataToPost.CUSCOD   = $("#CUSCOD").val();
        dataToPost.ADDRNO 	= $("#ADDRNO").val();
        dataToPost.ADDR1 	= $("#ADDR1").val();
        dataToPost.SOI      = $("#SOI").val();
        dataToPost.ADDR2 	= $("#ADDR2").val();
        dataToPost.MOOBAN 	= $("#MOOBAN").val();
        dataToPost.TUMB 	= $("#TUMB").val();
        dataToPost.AUMPCOD 	= (typeof $("#AUMPDES").find(":selected").val()     === "undefined" ? "": $("#AUMPDES").find(":selected").val());
        dataToPost.PROVCOD 	= (typeof $("#PROVDES").find(":selected").val()     === "undefined" ? "": $("#PROVDES").find(":selected").val());
        dataToPost.AUMPDES 	= (typeof $("#AUMPDES").find(":selected").text()    === "undefined" ? "": $("#AUMPDES").find(":selected").text());
        dataToPost.PROVDES 	= (typeof $("#PROVDES").find(":selected").text()    === "undefined" ? "": $("#PROVDES").find(":selected").text());
        dataToPost.ZIP      = (typeof $("#ZIP").find(":selected").val()         === "undefined" ? "": $("#ZIP").find(":selected").val());
        dataToPost.TELP 	= $("#TELP").val();
        dataToPost.MEMO1 	= $("#MEMO1").val();

        OBJbtneditAdrr = $.ajax({
            url: '/Customer_History/index.php/Welcome/SetAddr_Html',			
            data: dataToPost,
            type: 'POST',
            dataType: 'json',
            success: function(data){				
                if(!checkEmptyInputaddress()){    
					$("#dataAddress tbody").append(data.Tboby);
                    fn_address("edit");
					fn_reactive_addr(); // แก้ไขข้อมูลที่อยู่พนักงาน กรณีเพิ่มที่อยู่ใหม่
                    $window.destroy();
                }
				
				OBJbtneditAdrr = null;
            },
            beforeSend: function(){
                if(OBJbtneditAdrr !== null){
                    OBJbtneditAdrr.abort();
                }
            }
        });
    });
/*-----------------------------------Select2 อำเภอ จังหวัด รหัสไปรษณีย์-------------------------------------------------------------------------------*/	
    $('#AUMPDES').select2({        //อำเภอ
        placeholder: 'เลือก',
        ajax: {
            url: '/Customer_History/index.php/Welcome/kaumpselect',
            data: function (params) {
                dataToPost = new Object();
                dataToPost.now = (typeof $('#AUMPDES').find(':selected').val() === 'undefined' ? "":$('#AUMPDES').find(':selected').val());
                dataToPost.q = (typeof params.term === 'undefined' ? '' : params.term);
                dataToPost.provcod = (typeof $('#PROVDES').find(':selected').val() === 'undefined' ? "":$('#PROVDES').find(':selected').val()); //จังหวัด

                return dataToPost;    
            },
            dataType: 'json',
            delay: 1000,
            processResults: function (data) {
                return {
                        results: data
                };
            },
            cache: true
        },
        allowClear: true,
        multiple: false,
        dropdownParent: $('#AUMPDES').parent().parent(),
        //disabled: true,
        //theme: 'classic',
        width: '100%'
    });
    
    $('#PROVDES').select2({      //จัดหวัด
        placeholder: 'เลือก',
        ajax: {
            url: '/Customer_History/index.php/Welcome/kprovselect',
            data: function (params) {
                dataToPost = new Object();
                dataToPost.now = (typeof $('#PROVDES').find(':selected').val() === 'undefined' ? "":$('#PROVDES').find(':selected').val()); 
                dataToPost.q = (typeof params.term === 'undefined' ? '' : params.term);
                dataToPost.provcod1 = (typeof $('#AUMPDES').find(':selected').val() === 'undefined' ? "":$('#AUMPDES').find(':selected').val()); //อำเภอ
                return dataToPost;    
            },
            dataType: 'json',
            delay: 1000,
            processResults: function (data) {
                return {
                        results: data
                };
            },
            cache: true
        },
        allowClear: true,
        multiple: false,
        dropdownParent: $('#PROVDES').parent().parent(),
        //disabled: true,
        //theme: 'classic',
        width: '100%'
    });
    
    $('#ZIP').select2({        //รหัสไปรษณีย์
        placeholder: 'เลือก',
        ajax: {
            url: '/Customer_History/index.php/Welcome/kzipselect',
            data: function (params) {
                dataToPost = new Object();
                dataToPost.now = (typeof $('#ZIP').find(':selected').val() === 'undefined' ? "":$('#ZIP').find(':selected').val());
                dataToPost.q = (typeof params.term === 'undefined' ? '' : params.term);
                dataToPost.provcod2 = (typeof $('#PROVDES').find(':selected').val() === 'undefined' ? "":$('#PROVDES').find(':selected').val()); //จังหวัด

                return dataToPost;    
            },
            dataType: 'json',
            delay: 1000,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        allowClear: true,
        multiple: false,
        dropdownParent: $('#ZIP').parent().parent(),
        //disabled: true,
        //theme: 'classic',
        width: '100%'
    });

    $('#PROVDES').on("select2:select",function(){ //เลือกอำเภอโชว์จังหวัด
        $('#AUMPDES').val(null).trigger('change');
    });
    
    var JDjaump = null;
    $('#AUMPDES').on("select2:select",function(){
        dataToPost = new Object();
        dataToPost.aumpcod = (typeof $('#AUMPDES').find(":selected").val() === "undefined" ? "":$('#AUMPDES').find(":selected").val());
        JDjaump = $.ajax({
            url: '/Customer_History/index.php/Welcome/kgetProv',
            data: dataToPost,
            type: "POST",
            dataType: "json",
            success: function(data) {
                var newOption = new Option(data.PROVDES, data.PROVCOD, false, false);
                $('#PROVDES').empty().append(newOption).trigger('change');
            },
            beforeSend: function(){
                if(JDjaump != null){
                    JDjaump.abort();
                }
            }
        });
    });
    
    $('#ZIP').on("select2:select",function(){ //เลือกอำเภอโชว์รหัสไปรษณีย์
        $('#AUMPDES').val(null).trigger('change');
    });
    
    var Zip = null;
    $('#AUMPDES').on("select2:select",function(){
        dataToPost = new Object();
        dataToPost.aumpcod1 = (typeof $('#AUMPDES').find(":selected").val() === "undefined" ? "":$('#AUMPDES').find(":selected").val());

        Zip = $.ajax({
            url: '/Customer_History/index.php/Welcome/kshowZip',
            data: dataToPost,
            type: "POST",
            dataType: "json",
            success: function(data) {
                var newOption = new Option(data.AUMPCOD, data.PROVCOD, false, false);
                $('#ZIP').empty().append(newOption).trigger('change');
            },
            beforeSend: function(){
                if(Zip != null){
                    Zip.abort();
                }
            }
        });
    });
}

function checkEmptyInputaddress(){
	var isEmpty = false,
        ADDRNO  = $("#ADDRNO").val(),
        ADDR1   = $("#ADDR1").val();
    if(ADDRNO ==""){
        Lobibox.alert('info', {
            msg:"กรุณากรอกลำดับเป็นตัวเลข",
            onShow: function(){
                isEmpty = true;
            }
        });
    }
    else if(ADDR1 ==""){
        Lobibox.alert('info', {
            msg:"กรุณากรอกบ้านเลขที่",
            onShow: function(){
                isEmpty = true;
            }
        });
    }
    return isEmpty;
}

function fn_reactive_addr(){   
	var OBJeditaddress = null;			//แก้ไขที่อยู่ในตาราง---buttontable	
	$(".EditTableAddr").unbind("click");
	$(".EditTableAddr").click(function(){
		var btnthisedit = $(this); 	
		btnthisedit.parents('tr').remove();
		fn_loadFromADDR("edit",$(this)); //ฟอร์มกรอกที่อยู่ลูกค้า
	});
	$(".DelTableAddr").unbind('click');
	$(".DelTableAddr").click(function(){
		var btnthisdel = $(this);
		Lobibox.confirm({
			title: 'ยืนยันการทำรายการ',
			iconClass: false,
			msg: "คุณต้องการลบ ?",
			buttons: {
				ok : {
					'class': 'btn btn-primary',
					text: 'ยืนยัน',
					closeOnClick: true,
				},
				cancel : {
					'class': 'btn btn-danger',
					text: 'ยกเลิก',
					closeOnClick: true
				},
			},
			callback: function(lobibox, type){
				var btnType;
				if (type === 'ok'){
					btnthisdel.parents('tr').remove(); 
					fn_address();
				}
			}
		});
	});
}

/*
$(".DelTableAddr").click(function(){
	var btnthisdel = $(this);
	Lobibox.confirm({
		msg: "คุณแน่ใจว่าต้องการจะลบที่อยู่นี้",
		callback: function($this,type,){			
			if(type == 'yes'){
				btnthisdel.parents('tr').remove();
				fn_address();
			}
		}
	});
});
*/
function fn_address($action){
    $('#addrno1').empty().trigger('change');
    $('#addrno2').empty().trigger('change');
    $('#addrno3').empty().trigger('change');
    
    $(".EditTableAddr").each(function(){
        var newOption = new Option($(this).attr("ADDRNO"), $(this).attr("ADDRNO"), false, false);
        $('#addrno1').append(newOption).trigger('change');
        var newOption = new Option($(this).attr("ADDRNO"), $(this).attr("ADDRNO"), false, false);
        $('#addrno2').append(newOption).trigger('change');
        var newOption = new Option($(this).attr("ADDRNO"), $(this).attr("ADDRNO"), false, false);
        $('#addrno3').append(newOption).trigger('change');
    });
}
function CloseLobiwindow(address_ae,event){
   //alert(address_ae.attr("ADDRNO"));
    var OBJbtncloseAddr = null;
    if(event != "cancel"){
        dataToPost = new Object();
        dataToPost.ADDRNO   = $("#ADDRNO").val();
        dataToPost.ADDR1    = $("#ADDR1").val();
        dataToPost.SOI      = $("#SOI").val();
        dataToPost.ADDR2    = $("#ADDR2").val();
        dataToPost.MOOBAN   = $("#MOOBAN").val();
        dataToPost.TUMB     = $("#TUMB").val();
        dataToPost.AUMPCOD  = (typeof $("#AUMPDES").find(":selected").val()     === "undefined" ? "": $("#AUMPDES").find(":selected").val());
        dataToPost.PROVCOD  = (typeof $("#PROVDES").find(":selected").val()     === "undefined" ? "": $("#PROVDES").find(":selected").val());
        dataToPost.AUMPDES  = (typeof $("#AUMPDES").find(":selected").text()    === "undefined" ? "": $("#AUMPDES").find(":selected").text());
        dataToPost.PROVDES  = (typeof $("#PROVDES").find(":selected").text()    === "undefined" ? "": $("#PROVDES").find(":selected").text());
        dataToPost.ZIP      = (typeof $("#ZIP").find(":selected").val()         === "undefined" ? "": $("#ZIP").find(":selected").val());
        dataToPost.TELP     = $("#TELP").val();
        dataToPost.MEMO1    = $("#MEMO1").val();
    }else{
        dataToPost = new Object();
        dataToPost.ADDRNO 	= address_ae.attr("ADDRNO");
        dataToPost.ADDR1 	= address_ae.attr("ADDR1");
        dataToPost.SOI      = address_ae.attr("SOI");
        dataToPost.ADDR2 	= address_ae.attr("ADDR2");
        dataToPost.MOOBAN 	= address_ae.attr("MOOBAN");
        dataToPost.TUMB 	= address_ae.attr("TUMB");
        dataToPost.AUMPCOD 	= address_ae.attr("AUMPCOD");
        dataToPost.PROVCOD 	= address_ae.attr("PROVCOD");
        dataToPost.AUMPDES 	= address_ae.attr("AUMPDES");
        dataToPost.PROVDES 	= address_ae.attr("PROVDES");
        dataToPost.ZIP      = address_ae.attr("ZIP");
        dataToPost.TELP 	= address_ae.attr("TELP");
        dataToPost.MEMO1 	= address_ae.attr("MEMO1");
    }
    OBJbtncloseAddr = $.ajax({
        url: '/Customer_History/index.php/Welcome/SetAddr_Html',			
        data: dataToPost,
        type: 'POST',
        dataType: 'json',
        success: function(data){
            if(!checkEmptyInputaddress()){
                //$("#dataAddress tbody").append(data.Tboby);
				fn_reactive_addr(); // แก้ไขข้อมูลที่อยู่พนักงาน กรณีเพิ่มที่อยู่ใหม่
                fn_address("edit");
                OBJbtncloseAddr = null;
            }
        },
        beforeSend: function(){
            if(OBJbtncloseAddr !== null){
                OBJbtncloseAddr.abort();
            }
        }
    });
}

function SaveCustomerDB($window){
	//var Checknull = false;
	var CheckEmpty = "";
	if($("#NAME1").val()==""){CheckEmpty +="กรุณากรอกชื่อก่อนครับ\n"}
	if($("#NAME2").val()==""){CheckEmpty +="กรุณากรอกนามสกุลก่อนครับ"}
	if(CheckEmpty==""){
		dataToPost = new Object();
		dataToPost.CUSCOD   = $("#CUSCOD").val();
		dataToPost.GROUP1   = $("#GROUP1").val();
		dataToPost.GRADE    = $("#GRADE").val();
		dataToPost.SNAM     = $("#SNAM").val();
		dataToPost.NAME1    = $("#NAME1").val();
		dataToPost.NAME2    = $("#NAME2").val();
		dataToPost.NICKNM   = $("#NICKNM").val();
		dataToPost.BIRTHDT  = $("#BIRTHDT").val();
		dataToPost.IDCARD   = $("#IDCARD").val();
		dataToPost.IDNO     = $("#IDNO").val();
		dataToPost.ISSUBY   = $("#ISSUBY").val();
		dataToPost.ISSUDT   = $("#ISSUDT").val();
		dataToPost.EXPDT    = $("#EXPDT").val();
		dataToPost.AGE      = $("#AGE").val();
		dataToPost.NATION   = $("#NATION").val();
		dataToPost.OCCUP    = $("#OCCUP").val();
		dataToPost.OFFIC    = $("#OFFIC").val();
		dataToPost.MAXCRED  = $("#MAXCRED").val();
		dataToPost.MREVENU  = $("#MREVENU").val();
		dataToPost.YREVENU  = $("#YREVENU").val();
		dataToPost.MOBILENO = $("#MOBILENO").val();
		dataToPost.EMAIL1   = $("#EMAIL1").val();
		dataToPost.addrno2  = $("#addrno2").val();
		dataToPost.addrno3  = $("#addrno3").val();
		dataToPost.MEMOADD  = $("#MEMOADD").val();
		var ad = [];
		$(".EditTableAddr").each(function(){
			var adr =[];   
			adr.push($(this).attr('ADDRNO'));
			adr.push($(this).attr('ADDR1'));            
			adr.push($(this).attr('SOI'));
			adr.push($(this).attr('ADDR2'));
			adr.push($(this).attr('MOOBAN'));
			adr.push($(this).attr('TUMB'));
			adr.push($(this).attr('AUMPCOD'));
			adr.push($(this).attr('PROVCOD'));
			adr.push($(this).attr('ZIP'));
			adr.push($(this).attr('TELP'));
			adr.push($(this).attr('MEMO1'));
			
			ad.push(adr);
		});        
		dataToPost.address  = ad; 
		$.ajax({
			url:'/Customer_History/index.php/Welcome/InsertCustomer_DB'
			,data:dataToPost
			,type:"POST"
			,dataType: "json"
			,success : function (data){
				if(!data.error){
					if(data.tablehtml == "K"){
						Lobibox.alert('warning', {
							msg:"กรุณาเพิ่มที่อยู่ก่อนครับ",
							/*onShow : function(){
								Checknull = true;
							}*/
						});
					}else{
						Lobibox.alert('success', {
							msg:"บันทึกสำเร็จ  รหัสลูกค้า: "+data.html
						});
						$window.destroy();	
					}
				}
			}
		});
	}else{
		Lobibox.alert('warning', {
			msg:CheckEmpty,
			/*onShow : function(){
				Checknull = true;
			}*/
		});
	}
	//return Checknull;	
}
function UpdateCustomerDB($window){
	dataToPost = new Object();
	dataToPost.CUSCOD   = $("#CUSCOD").val();
	dataToPost.GROUP1   = $("#GROUP1").val();
	dataToPost.GRADE    = $("#GRADE").val();
	dataToPost.SNAM     = $("#SNAM").val();
	dataToPost.NAME1    = $("#NAME1").val();
	dataToPost.NAME2    = $("#NAME2").val();
	dataToPost.NICKNM   = $("#NICKNM").val();
	dataToPost.BIRTHDT  = $("#BIRTHDT").val();
	dataToPost.IDCARD   = $("#IDCARD").val();
	dataToPost.IDNO     = $("#IDNO").val();
	dataToPost.ISSUBY   = $("#ISSUBY").val();
	dataToPost.ISSUDT   = $("#ISSUDT").val();
	dataToPost.EXPDT    = $("#EXPDT").val();
	dataToPost.AGE      = $("#AGE").val();
	dataToPost.NATION   = $("#NATION").val();
	dataToPost.OCCUP    = $("#OCCUP").val();
	dataToPost.OFFIC    = $("#OFFIC").val();
	dataToPost.MAXCRED  = $("#MAXCRED").val();
	dataToPost.MREVENU  = $("#MREVENU").val();
	dataToPost.YREVENU  = $("#YREVENU").val();
	dataToPost.MOBILENO = $("#MOBILENO").val();
	dataToPost.EMAIL1   = $("#EMAIL1").val();
	dataToPost.addrno2  = $("#addrno2").val();
	dataToPost.addrno3  = $("#addrno3").val();
	dataToPost.MEMOADD  = $("#MEMOADD").val();
	var ad = [];
	$(".EditTableAddr").each(function(){
		var adr =[];   
		adr.push($(this).attr('ADDRNO'));
		adr.push($(this).attr('ADDR1'));            
		adr.push($(this).attr('SOI'));
		adr.push($(this).attr('ADDR2'));
		adr.push($(this).attr('MOOBAN'));
		adr.push($(this).attr('TUMB'));
		adr.push($(this).attr('AUMPCOD'));
		adr.push($(this).attr('PROVCOD'));
		adr.push($(this).attr('ZIP'));
		adr.push($(this).attr('TELP'));
		adr.push($(this).attr('MEMO1'));
		
		ad.push(adr);
	});        
	dataToPost.address  = ad; 
	$.ajax({
		url:'/Customer_History/index.php/Welcome/Update_Customer_DB'
		,data:dataToPost
		,type:"POST"
		,dataType: "json"
		,success : function (data){
			if(!data.error){
				if(data.tablehtml=="K"){
					Lobibox.alert('warning',{
						msg:"กรุณาเพิ่มที่อยู่ก่อนครับ"
					});
				}else{			
					Lobibox.alert('success', {
						title:"สำเร็จ",
						msg:"แก้ไขประวัติลูกค้าสำเร็จ"
					});
					$window.destroy();
				}
			}
		}
	});
}

