<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome extends CI_Controller {
    function __construct() { 
        parent::__construct();
    }
    function index(){
        $this->load->view('welcome_message');
    }
	function dashboard(){
		$html = "
            <center><h2>ยินดีต้อนรับ</h2><br></center><br><br>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-2'>
							ค้นหาประวัติลูกค้า:
                        </div>
						<div class='col-sm-6'>
                            <input id ='SearchInput' value='%' class='form-control form-control-lg form-control-borderless' type='search' placeholder='กรุณาระบุคำค้น' required='required'>
                        </div>
						<div class='col-sm-2'>
                            <button type='button' id='SearchSubmit' class='btn btn-lg btn-warning btn-block fa fa-search'>ค้นหา</button>
                        </div>
                        <div class='col-sm-2'>
                            <button type='button' id='AddEmployeeCustomer' class='btn btn-lg btn btn-primary btn-block fa fa-plus-square'>เพิ่มประวัติลูกค้า</button>
                        </div><br><br>
						<div id= 'Showtable_SearchDB' class='col-sm-12'></div>
                    </div>
                </div>
            ";

		//$html .= "<script src='".base_url()."js/Customer_History.js?=".date("His")."'>";

		$html .= "<script src='".base_url()."js/History_Customer.js?=".date("His")."'>";
        echo ($html);
	}
	function Search_Show_QueryDB(){
		$SearchInput = $_REQUEST['SearchInput'];
		
		$sql = "select * from CUSTMAST where CUSCOD like'%".$SearchInput."%' or NAME1 like'%".$SearchInput."%' order by CUSCOD";
        
        $query = $this->db->query($sql)->result();
        $html = "<div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <table width='100%' class='table table table-hover' id='ShowtableHistoryDB'>
                            <thead>
                                <tr class='bg-success'>
                                    <th>รหัสลูค้า</th>
                                    <th>ชื่อ-สกุล</th>
                                    <th>วัน/เดือน/ปี เกิด</th>
                                    <th>อายุ</th>
                                    <th>อาชีพ</th>
                                    <th>ที่อยู่</th>
                                    <th>แก้ไขประวัติลูกค้า</th>
									<!--th>ลบ</th-->
                                </tr>
                            </thead>
                            <tbody>";
							
                    foreach ($query as $row){
                        $html .="<tr>
                                    <td>".$row->CUSCOD."</td>
                                    <td>".$row->NAME1." ".$row->NAME2."</td>
                                    <td>".$this->dateselectshow($row->BIRTHDT)."</td>
                                    <td>".$row->AGE."</td>
                                    <td>".$row->OCCUP."</td>  
                                    <td><button id='' CUSCOD =".$row->CUSCOD." class='ShowAddressDB btn btn-sm btn-primary btn-block fa '>แสดงที่อยู่</button></td>    
                                    <td>
										<button ID_CUSCOD=".$row->CUSCOD."' class='EditHistoryCustomer btn btn-sm btn-warning btn-block fa fa-edit'>แก้ไขประวัติลูกค้า</button>
                                    </td>
									<!--td><button ID_CUSCOD_DEL='".$row->CUSCOD."' class='DelHistoryCustomer btn btn-sm btn-danger btn-block'>ลบ</button></td-->
                                </tr>";
                    }
                    $html .="</tbody>
                            <tfoot> 
                            </tfoot>
                        </table>
                    </div>    
                </div>
                ";
       $response = array("html"=>$html);
       echo json_encode($html);
	}
	
	function Show_Address_TableDB(){
		$IDCUSCOD = $_POST ['CUSCOD'];
        $sql ="
            select * from CUSTADDR A
            inner join SETAUMP B on A.AUMPCOD=B.AUMPCOD 
            inner join SETPROV C on B.PROVCOD=C.PROVCOD
            where CUSCOD='".$IDCUSCOD."'
            order by ADDRNO";
        $query = $this->db->query($sql)->result();
        $html = "<div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <table class='table' id=''>
                            <thead>
                                <tr class='bg-primary'>
                                    <th>ลำดับ</th>
                                    <th>ที่อยู่</th>
                                    <th>เบอร์ติดต่อ</th>
                                    <th>หมายเหตุ</th>
                                </tr>
                            </thead>
                            <tbody>";
                        foreach ($query as $row){      
                        $html .="<tr>
                                    <td>".$row->ADDRNO."</td>
                                    <td>บ้านเลขที่ ".$row->ADDR1." ซอย ".$row->SOI." ถนน ".$row->ADDR2." ตำบล ".$row->TUMB." หมู่บ้าน ".$row->MOOBAN." อำเภอ ".$row->AUMPDES." จังหวัด ".$row->PROVDES." รหัสไปรษณีย์ ".$row->ZIP."</td>
                                    <td>".$row->TELP."</td>
                                    <td>".$row->MEMO1."</td>    
                                </tr>";
                        }
                    $html .="</tbody>
                            <tfoot>
                                
                            </tfoot>
                        </table>
                    </div>    
                </div>              
                ";
        $response = array("html" => $html);
        echo json_encode($response);
	}
	
	function Form_Add_Employee(){
		/*$sql = "select * from SIRNAM";
        $query = $this->db->query($sql);
        $SIRCOD = "";
        foreach ($query->result() as $row){
            $SIRCOD .="<option value=".$row->SIRCOD.">".$row->SIRNAM."</option>";
        } 
		
        $sqlA = "select * from ARGROUP";
        $queryA = $this->db->query($sqlA);
        $ARGCOD = "";
        foreach ($queryA->result() as $rowA){
            $ARGCOD .="<option value=".$rowA->ARGCOD.">".$rowA->ARGDES."</option>";   
        } 
		
        $sqlC = "select * from SETGRADCUS";
        $queryC = $this->db->query($sqlC);
        $GRDCOD = "";
        foreach ($queryC->result() as $rowC){
            $GRDCOD .="<option value=".$rowC->GRDCOD.">".$rowC->GRDCOD." ".$rowC->GRDDES."</option>";
        }*/
        $html ="
            <center><h2>ประวัติลูกค้า</h2><br></center><br><br>
            <div id = historypersons>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							รหัสลูกค้า
                            <input type='text' class='form-control input-sm' id='CUSCOD' value='Auto' disabled>
                        </div>
                        <div class='col-sm-4'>
							กลุ่มลูกค้า
                            <select type='text' class='form-control' id='GROUP1'>
                                
                            </select>
                        </div>
                        <div class='col-sm-4'>
							เกรด
                            <select type='text' class='form-control' id='GRADE'>
                                
                            </select>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							คำนำหน้า
                            <select type='text' class='form-control' id='SNAM'>
                                
                            </select>
                        </div>
                        <div class='col-sm-4'>
							ชื่อ
                            <input type='text' class='form-control input-sm checkvalue' id='NAME1'>
                        </div>
                        <div class='col-sm-4'>
							นามสกุล
                            <input type='text' class='form-control input-sm checkvalue' id='NAME2'>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							ชื่อเล่น
                            <input type='text' class='form-control input-sm checkvalue' id='NICKNM'>
                        </div>
                        <div class='col-sm-4'>
							วัน/เดือน/ปี เกิด
                            <input type='text' class='form-control input-sm checkvalue' id='BIRTHDT' data-date-format= 'yyyy-mm-dd'>
                        </div>
                        <div class='col-sm-4'>
							ประเภทบัตรประจำตัว
                            <select type='text' class='form-control input-sm checkvalue' id='IDCARD'>
                                <option  value=''>---</option>
                                <option  value='A'>A</option>
                                <option  value='B'>B</option>
                                <option  value='C'>C</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							เลขที่
                            <input type='text' class='form-control input-sm checkvalue' id='IDNO'>
                        </div>
                        <div class='col-sm-4'>
							ออกโดย
                            <input type='text' class='form-control input-sm checkvalue' id='ISSUBY'>
                        </div>
                        <div class='col-sm-4'>
							วัน/เดือน/ปี ที่ออกบัตร
                            <input type='text' class='form-control input-sm checkvalue' id='ISSUDT' data-date-format= 'yyyy-mm-dd'>
                        </div>    
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							วัน/เดือน/ปี บัตรหมดอายุ
                            <input type='text' class='form-control input-sm checkvalue' id='EXPDT' data-date-format= 'yyyy-mm-dd'>
                        </div>
                        <div class='col-sm-4'>
							อายุ
                            <input type='number' class='form-control input-sm checkvalue' id='AGE'>
                        </div>
                        <div class='col-sm-4'>
							สัญชาติ
                            <input type='text' class='form-control input-sm checkvalue' id='NATION'>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							อาชีพ
                            <input type='text' class='form-control input-sm checkvalue' id='OCCUP'>
                        </div>
                        <div class='col-sm-4'>
							สถานที่ทำงาน
                            <input type='text' class='form-control input-sm checkvalue' id='OFFIC'>
                        </div>
                        <div class='col-sm-4'>
							วงเงินเครดิต
                            <input type='text' class='form-control input-sm checkvalue' id='MAXCRED'>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							รายได้ต่อเดือน
                             <select type='text' class='form-control input-sm checkvalue' id='MREVENU'>
                                <option  value=''>รายได้ต่อเดือน</option>
                                <option  value='1'>น้อยกว่า 10,000</option>
                                <option  value='2'>10,000-15,000</option>
                                <option  value='3'>15,000-25,000</option>
                                <option  value='4'>30,000 ขึ้นไป</option>
                            </select>
                            </div>
                        <div class='col-sm-4'>
							รายได้พิเศษต่อปี
                            <select type='text' class='form-control input-sm checkvalue' id='YREVENU' >
                                <option  value=''>รายได้พิเศษต่อปี</option>
                                <option  value='1'>น้อยกว่า 10,000</option>
                                <option  value='2'>10,000-15,000</option>
                                <option  value='3'>15,000-25,000</option>
                                <option  value='4'>30,000 ขึ้นไป</option>
                            </select>
                        </div>
                        <div class='col-sm-4'>
							เบอร์โทร
                            <input type='text' class='form-control input-sm checkvalue' id='MOBILENO' maxlength='10'>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							อีเมล์
                            <input type='email' class='form-control input-sm checkvalue' id='EMAIL1'><br>
                        </div>
                    </div>
                </div>         
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <table width='100%' class='table checkvalue' id='dataAddress'>
                            <thead>
                                <tr class='bg-primary'>
                                    <th>ลำดับ</th>
                                    <th>ที่อยู่</th>
                                    <th>เบอร์ติดต่อ</th>
                                    <th>หมายเหตุ</th>
                                    <th>แก้ไข</th>
                                    <th>ลบ</th>
                                </tr>
                            </thead>
                            <tbody id='AA'>
                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan='6'>
                                        <button id='btnAddAddressFirst' class='btn btn-sm btn-primary btn-block glyphicon glyphicon-plus'>เพิ่มที่อยู่</button>
                                    </td>	
                                </tr>
                            </tfoot>
                        </table>
                    </div>    
                </div>       
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							ที่อยู่ตามทะเบียนบ้าน
                            <select type='text' class='form-control' id='addrno1'></select>
                        </div>
                        <div class='col-sm-4'>
							ที่อยู่ปัจจุบัน
                            <select type='text' class='form-control' id='addrno2'></select>
                        </div>
                        <div class='col-sm-4'>
							ที่อยู่ที่อยู่ที่ส่งจดหมาย
                            <select type='text' class='form-control' id='addrno3'></select>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-6'>
							หมายเหตุ
                            <textarea class='form-control' id='MEMOADD'></textarea>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-2'>
                    <div class='row'><br>
                        <div class='col-sm-5'>
                            <button type='button' id='SaveCustomerDB' class='btn btn-sm btn-primary btn-block'>บันทึก</button>
                        </div>
                        <div class='col-sm-5'>
                            <button type='button' id='Cancelcustomer' class='btn btn-sm btn-danger btn-block'>ยกเลิก</button>
                        </div><br><br>
                    </div>    
                </div>
            </div>
        ";
        $response = array("html"=>$html);
        echo json_encode($response);
	}
	function GROUP1_Select2(){
		$now = $_REQUEST["now"];
        $q   = $_REQUEST["q"];
        
        $sql = "
            select ARGCOD,ARGDES from ARGROUP
            where ARGCOD='".$now."' 

            union
            select top 20 ARGCOD,ARGDES from ARGROUP
            where ARGDES like '%".$q."%' 
            ";
        
        $query = $this->db->query($sql);

        $json = array();
        if($query->row()){
            foreach($query->result() as $row){
                $json[] = array(
                    "id" =>str_replace(chr(0),"",$row->ARGCOD),
                    "text" =>str_replace(chr(0),"",$row->ARGDES),					 
                );
            }
        }
        echo json_encode($json);
	}
	function GRADE_Select2(){
		$now = $_REQUEST["now"];
        $q   = $_REQUEST["q"];
        
        $sql = "select GRDCOD,GRDDES from SETGRADCUS
            where GRDCOD ='".$now."' 

            union
            select GRDCOD,GRDDES from SETGRADCUS
            where GRDDES like '%".$q."%' 
            ";
        
        $query = $this->db->query($sql);

        $json = array();
        if($query->row()){
            foreach($query->result() as $row){
                $json[] = array(
                    "id" =>str_replace(chr(0),"",$row->GRDCOD),
                    "text" =>str_replace(chr(0),"",$row->GRDCOD).str_replace(chr(0),"",$row->GRDDES),					 
                );
            }
        }
        echo json_encode($json);
	}
	function SNAM_Select2(){
		$now = $_REQUEST["now"];
        $q   = $_REQUEST["q"];
        
        $sql = "
            select SIRCOD,SIRNAM from SIRNAM
            where SIRCOD = '".$now."'

            union
            select SIRCOD,SIRNAM from SIRNAM
            where SIRNAM like '%".$q."%' 
            ";
        
        $query = $this->db->query($sql);

        $json = array();
        if($query->row()){
            foreach($query->result() as $row){
                $json[] = array(
                    "id" =>str_replace(chr(0),"",$row->SIRCOD),
                    "text" =>str_replace(chr(0),"",$row->SIRNAM),					 
                );
            }
        }
        echo json_encode($json);
	}
	function Form_Address_Customer(){
		$arrs = array();
        $arrs["ADDRNO"]	  = (!isset($_POST["ADDRNO"])?  "":$_POST["ADDRNO"]);
        $arrs["ADDR1"] 	  = (!isset($_POST["ADDR1"])?   "":$_POST["ADDR1"]);
        $arrs["SOI"] 	  = (!isset($_POST["SOI"])?     "":$_POST["SOI"]);
        $arrs["ADDR2"] 	  = (!isset($_POST["ADDR2"])?   "":$_POST["ADDR2"]);
        $arrs["MOOBAN"]   = (!isset($_POST["MOOBAN"])?  "":$_POST["MOOBAN"]);
        $arrs["TUMB"] 	  = (!isset($_POST["TUMB"])?    "":$_POST["TUMB"]);
        $arrs["AUMPCOD"]  = (!isset($_POST["AUMPCOD"])? "":$_POST["AUMPCOD"]);
        $arrs["PROVCOD"]  = (!isset($_POST["PROVCOD"])? "":$_POST["PROVCOD"]);
        $arrs["AUMPDES"]  = (!isset($_POST["AUMPDES"])? "":$_POST["AUMPDES"]);
        $arrs["PROVDES"]  = (!isset($_POST["PROVDES"])? "":$_POST["PROVDES"]);
        $arrs["ZIP"] 	  = (!isset($_POST["ZIP"])?     "":$_POST["ZIP"]);
        $arrs["TELP"] 	  = (!isset($_POST["TELP"])?    "":$_POST["TELP"]);
        $arrs["MEMO1"] 	  = (!isset($_POST["MEMO1"])?   "":$_POST["MEMO1"]);
        $arrs["ACTION"]   = $_POST["ACTION"];
		
		$DIS = "";
		if($arrs["ADDRNO"]!=""){
			$DIS = "disabled";
		}
        $html = "
            <div id = 'Lobiboxwindow'>
                <center><h2>ที่อยู่ลูกค้า</h2><br></center><br>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-12'>
							ลำดับ
                            <input type='number' class='form-control' id='ADDRNO' value='".$arrs["ADDRNO"]."' ".$DIS.">
                        </div>
                        <div class='col-sm-12'>
							บ้านเลขที่
                            <input type='text' class='form-control' id='ADDR1' value='".$arrs["ADDR1"]."'>
                        </div>    
                        <div class='col-sm-12'>
							ซอย
                            <input type='text' class='form-control' id='SOI' value='".$arrs["SOI"]."'>
                        </div>
                        <div class='col-sm-12'>
							ถนน
                            <input type='text' class='form-control' id='ADDR2' value='".$arrs["ADDR2"]."'>
                        </div>
                        <div class='col-sm-12'>
							หมู่บ้าน
                            <input type='text' class='form-control' id='MOOBAN' value='".$arrs["MOOBAN"]."'>
                        </div>
                        <div class='col-sm-12'>
							ตำบล
                            <input type='text' class='form-control' id='TUMB' value='".$arrs["TUMB"]."'>
                        </div>
                        <div class='col-sm-12'>
							อำเภอ
                            <select type='text' id='AUMPDES' class='form-control'>
                                <option value='".$arrs["AUMPCOD"]."'>".$arrs["AUMPDES"]."</option>
                            </select>
                        </div>
                        <div class='col-sm-12'>
							จังหวัด
                            <select type='text' id='PROVDES' class='form-control'>
                                <option value='".$arrs["PROVCOD"]."'>".$arrs["PROVDES"]."</option>
                            </select>
                        </div>
                        <div class='col-sm-12'>
							รหัสไปรษณีย์
                            <select type='text' id='ZIP' class='form-control'>
                                <option value='".$arrs["ZIP"]."'>".$arrs["ZIP"]."</option>
                            </select>
                        </div>
                        <div class='col-sm-12'>
							เบอร์โทรศัพท์ติดต่อ
                            <input type='text' class='form-control' id='TELP' value='".$arrs["TELP"]."'>
                        </div>
                        <div class='col-sm-12'>
							หมายเหตุ
                            <textarea class='form-control' rows='2' cols='30' id='MEMO1' value='".$arrs["MEMO1"]."'>".$arrs["MEMO1"]."</textarea><br>
                        </div>
                    </div>
                </div>
            </div>
		";
		if($arrs["ACTION"] == "add"){//btnAddAddr
			$html .="
				<div class='row col-sm-12'>
					<div class='col-sm-6'>
						<button id='btnAddAddrTableHtml' class='btn btn-block btn-primary'>เพิ่ม</button><br>					
					</div>
					<div class='col-sm-6'>
						<button id='btnWACloseAdd' class='btn btn-block btn-danger'>ยกเลิก</button><br>
					</div>
				</div>
			";
		}else{
			$html .="
				<div class='row col-sm-12'>
					<div class='col-sm-6'>
						<button id='btneditAdrrTableHtml' class='btn btn-block btn-warning'>แก้ไข</button><br>					
					</div>
					<div class='col-sm-6'>
						<button id='btnWAClose' class='btn btn-block btn-danger'>ยกเลิก</button><br>
					</div>
				</div>
			";
		}
        $response = array("html" => $html);
        echo json_encode($response);
	}
	function SetAddr_Html(){ //เพิ่มข้อมูลในตาราง
		$arrs = array(); 
		$arrs["ADDRNO"]  = $_POST["ADDRNO"];
        $arrs["ADDR1"]   = $_POST["ADDR1"];
        $arrs["SOI"] 	 = $_POST["SOI"];
        $arrs["ADDR2"] 	 = $_POST["ADDR2"];
        $arrs["MOOBAN"]  = $_POST["MOOBAN"];
        $arrs["TUMB"] 	 = $_POST["TUMB"];
        $arrs["AUMPCOD"] = $_POST["AUMPCOD"];
        $arrs["PROVCOD"] = $_POST["PROVCOD"];
        $arrs["AUMPDES"] = $_POST["AUMPDES"];
        $arrs["PROVDES"] = $_POST["PROVDES"];
        $arrs["ZIP"] 	 = $_POST["ZIP"];
        $arrs["TELP"] 	 = $_POST["TELP"];
        $arrs["MEMO1"] 	 = $_POST["MEMO1"];
		
        $address = "";
        if($arrs["ADDR1"] != ""){
			$address .= "บ้านเลขที่".$arrs["ADDR1"];
        }
        if($arrs["SOI"] != ""){
			$address .= " ซอย".$arrs["SOI"];
        }
        if($arrs["ADDR2"] != ""){
			$address .= " ถนน".$arrs["ADDR2"];
        }
        if($arrs["MOOBAN"] != ""){
			$address .= " หมู่บ้าน".$arrs["MOOBAN"];
        }
        if($arrs["TUMB"] != ""){
			$address .= " ตำบล".$arrs["TUMB"];
        }
        if($arrs["AUMPDES"] != ""){
			$address .= " อำเภอ".$arrs["AUMPDES"];
        }
        if($arrs["PROVDES"] != ""){
			$address .= " จังหวัด".$arrs["PROVDES"];
        }
        if($arrs["ZIP"] != ""){
			$address .= " ".$arrs["ZIP"];
        }
		
        $Tboby = "
            <tr>
                <td class='data1'>".$arrs["ADDRNO"]."</td>
                <td class='data2'>".$address."</td>
                <td class='data3'>".$arrs["TELP"]."</td>
                <td class='data4'>".$arrs["MEMO1"]."</td>
                <td>
                    <button class='EditTableAddr btn btn-sm btn-warning fa fa-edit' 
                        ADDRNO='".$arrs["ADDRNO"]."'
                        ADDR1='".$arrs["ADDR1"]."'
                        SOI='".$arrs["SOI"]."'
                        ADDR2='".$arrs["ADDR2"]."'
                        MOOBAN='".$arrs["MOOBAN"]."'
                        TUMB='".$arrs["TUMB"]."'
                        AUMPCOD='".$arrs["AUMPCOD"]."'
                        PROVCOD='".$arrs["PROVCOD"]."'
                        AUMPDES='".$arrs["AUMPDES"]."'
                        PROVDES='".$arrs["PROVDES"]."'
                        ZIP='".$arrs["ZIP"]."'
                        TELP='".$arrs["TELP"]."'
                        MEMO1='".$arrs["MEMO1"]."'
					>แก้ไข</button>
                </td>
                <td>
                    <button ADDRNO='".$arrs["ADDRNO"]."' class='DelTableAddr btn btn-sm btn-danger fa fa-trash'>ลบ</button>
                </td>
            </tr>
            ";	
        $response = array("Tboby"=>$Tboby);
        echo json_encode($response);
	}
	function kaumpselect(){ //อำเภอ
		$now = $_REQUEST["now"];
        $q 	 = $_REQUEST["q"];
        $provcod = $_REQUEST["provcod"];

        $cond = "";
        if($provcod == ""){
            $cond = "";
        }else{
            $cond = " and PROVCOD='".$provcod."'";
        }
        $sql = "
            select AUMPCOD,AUMPDES from SETAUMP
            where AUMPCOD='".$now."' 

            union
            select top 20 AUMPCOD,AUMPDES from SETAUMP
            where AUMPDES like '%".$q."%' ".$cond."
            ";
        $query = $this->db->query($sql);

        $json = array();
        if($query->row()){
            foreach($query->result() as $row){
                $json[] = array(
                    "id" => $row->AUMPCOD,
                    "text" => $row->AUMPDES,					 
                );
            }
        }
        echo json_encode($json);
	}
	function kprovselect(){		//จังหวัด
		$now = $_REQUEST["now"];
        $q 	 = $_REQUEST["q"];
        $provcod1 = $_REQUEST["provcod1"];

        $cond = "";
        if($provcod1 == ""){
            $cond = "";
        }else{
            $cond = " and b.AUMPCOD='".$provcod1."'";
        }
        $sql = "
            select a.PROVCOD,a.PROVDES from SETPROV a 
            left join SETAUMP b on a.PROVCOD=b.PROVCOD
            where a.PROVCOD='".$now."'                        

            union
            select top 20 * from (
                select distinct a.PROVCOD,a.PROVDES from SETPROV a
                left join SETAUMP b on a.PROVCOD=b.PROVCOD
                where a.PROVDES like '%".$q."%' ".$cond." 			
            ) as data
        ";
       
        $query = $this->db->query($sql);
        $json = array();
        if($query->row()){
            foreach($query->result() as $row){
                $json[] = array(
                    "id" => $row->PROVCOD,
                    "text" => $row->PROVDES,					 
                );
            }
        }
        echo json_encode($json);
	}
	function kzipselect(){		//รหัสไปรษณีย์
		$now = $_REQUEST["now"];
        $q 	 = $_REQUEST["q"];
        $provcod2 = $_REQUEST["provcod2"];

        $cond = "";
        if($provcod2 == ""){
            $cond = "";
        }else{
            $cond = " and b.AUMPCOD='".$provcod2."'";
        }
        $sql = "
            select PROVCOD,AUMPCOD from SETAUMP
            where PROVCOD='".$now."' 

            union
            select top 20 PROVCOD,AUMPCOD from SETAUMP
            where AUMPCOD like '%".$q."%' $cond 
        ";
        
        $query = $this->db->query($sql);
        $json = array();
        if($query->row()){
            foreach($query->result() as $row){
                $json[] = array(
                    "id" => $row->PROVCOD,
                    "text" => $row->AUMPCOD,					 
                );
            }
        }
        echo json_encode($json);
	}
	function kgetProv(){		//เลือกอำเภอโชว์จังหวัด
		$aumpcod = $_POST["aumpcod"];
        $sql ="
            select * from SETPROV A
			inner join SETAUMP B on A.PROVCOD = B.PROVCOD
            where AUMPCOD='".$aumpcod."'
            ";
        $query = $this->db->query($sql);

        $data = array();
        if($query->row()){
            foreach($query->result() as $row){
                $data["PROVCOD"] = $row->PROVCOD;
                $data["PROVDES"] = $row->PROVDES;
            }
        }
        echo json_encode($data);
	}
	function kshowZip(){		//เลือกอำเภอโชว์รหัสไปรษณีย์
		$aumpcod1 = $_POST["aumpcod1"];
        $sql = "
            select * from SETPROV A
			inner join SETAUMP B on A.PROVCOD = B.PROVCOD
            where AUMPCOD='".$aumpcod1."'
        ";
        $query = $this->db->query($sql);
        $data = array();
        if($query->row()){
            foreach($query->result() as $row){
                $data["AUMPCOD"] = $row->AUMPCOD;
                $data["AUMPCOD"] = $row->AUMPCOD;
            }
        }
        echo json_encode($data);
	}
	function InsertCustomer_DB(){
        $GROUP1      = $_POST["GROUP1"];
        $GRADE       = $_POST["GRADE"];
        $SNAM        = $_POST["SNAM"];
        $NAME1       = $_POST["NAME1"];
        $NAME2       = $_POST["NAME2"];
        $NICKNM      = $_POST["NICKNM"];
        $BIRTHDT     = $this->dateformatsql($_POST["BIRTHDT"]);
        $IDCARD      = $_POST["IDCARD"];
        $IDNO        = $_POST["IDNO"];
        $ISSUBY      = $_POST["ISSUBY"];
        $ISSUDT      = $this->dateformatsql($_POST["ISSUDT"]);
        $EXPDT       = $this->dateformatsql($_POST["EXPDT"]);
        $AGE         = $_POST["AGE"];
        $NATION      = $_POST["NATION"];
        $OCCUP       = $_POST["OCCUP"];
        $OFFIC       = $_POST["OFFIC"];
        $MAXCRED     = $_POST["MAXCRED"];
        $MREVENU     = $_POST["MREVENU"];
        $YREVENU     = $_POST["YREVENU"];
        $MOBILENO    = $_POST["MOBILENO"];
        $EMAIL1      = $_POST["EMAIL1"];
        $addrno2     = $_POST["addrno2"];
        $addrno3     = $_POST["addrno3"];
        $MEMOADD     = $_POST["MEMOADD"];
        
		if(isset($_POST['address'])){}else{
			$tablehtml = "K";
			$response = array("tablehtml"=>$tablehtml);
			echo json_encode ($response);
			exit;
		}
		
        $ADDR        = $_POST["address"];
		
        
        $sql_insertaddr = "";		//บันทึกที่อยู่ของลูกค้าเข้าฐานข้อมูล
        $sizeArr = count($ADDR);
        for($P=0; $P < $sizeArr; $P++){
			$sql_insertaddr .="
				insert into CUSTADDR(
					[CUSCOD],[ADDRNO],[ADDR1],[ADDR2],[TUMB],[AUMPCOD],[PROVCOD]
					,[ZIP],[TELP],[MEMO1],[ACPDT],[USERID],[PICT1],[MOOBAN],[SOI]
				)values(
					@D,'".$ADDR[$P][0]."','".$ADDR[$P][1]."','".$ADDR[$P][3]."'
					,'".$ADDR[$P][5]."','".$ADDR[$P][6]."','".$ADDR[$P][7]."','".$ADDR[$P][8]."'
					,'".$ADDR[$P][9]."','".$ADDR[$P][10]."',null,null,null,'".$ADDR[$P][4]."'
					,'".$ADDR[$P][2]."'
					)
				";
        }		//ในส่วนของบันทึกประวัติลูกค้าทั้งหมดเข้าสู่ฐานข้อมูล
		
		$sql_insert ="
            declare @A varchar(20)=(SELECT TOP 1 [CUSCOD] FROM [HIC3].[dbo].[CUSTMAST] order by [CUSCOD] desc);
            declare @B varchar(20)='1'+right(@A,9);
            declare @C varchar(20)=+right(@B+1,9);
            declare @D varchar(20)='TJ-'+@C;
            select @D
            begin tran position
            begin try
                insert into CUSTMAST(
                    [CUSCOD],[GROUP1],[SNAM],[NAME1],[NAME2],[NICKNM],[BIRTHDT],[ADDRNO],[IDCARD],[IDNO],[ISSUBY]
                    ,[ISSUDT],[EXPDT],[AGE],[NATION],[OCCUP],[OFFIC],[BOSSNM],[GRADE],[ACPDT],[MEMO1],[USERID],[PICT1]
                    ,[MINCOME],[YINCOME],[MAXCRED],[MREVENU],[YREVENU],[MEMBCOD],[MOBILENO],[APPVCODE],[SIRCOD]
                    ,[CUSTTYPE],[ADDRNO2],[ADDRNO3],[EMAIL1],[EMAIL2]
                )values(
                    @D,'$GROUP1','$SNAM','$NAME1'
                    ,'$NAME2','$NICKNM ','$BIRTHDT',NULL,'$IDCARD'
                    ,'$IDNO','$ISSUBY','$ISSUDT','$EXPDT','$AGE'
                    ,'$NATION','$OCCUP','$OFFIC',NULL,'$GRADE',NULL
                    ,'$MEMOADD',NULL,NULL,NULL,NULL,'$MAXCRED','$MREVENU'
                    ,'$YREVENU',NULL,'$MOBILENO',NULL,NULL,NULL,'$addrno2'
                    ,'$addrno3','$EMAIL1',NULL
                    )
            ".$sql_insertaddr."	
			commit tran position;
			end try
			begin catch
				rollback tran position;
			end catch
			"; 
        $this->db->query($sql_insert);
        
        $sql_C ="SELECT * FROM CUSTMAST WHERE CUSCOD = (SELECT MAX(CUSCOD) FROM CUSTMAST)"; //ในส่วนของโชว์ไอดีพนักงานโดยสร้างอัตโนมัติจากการบันทึกข้อมูลในแต่ละครั้ง
        $query = $this->db->query($sql_C);
		
        $ID_AUTO = "";
        foreach ($query->result() as $row){
            $ID_AUTO .= $row->CUSCOD;
        }  
        $html= $ID_AUTO;
        $response = array("html"=>$html);
        echo json_encode($response);
	}
	function dateselectshow($date){
        if ($date!=""){
            return substr($date,8,2)."-".substr($date,5,2)."-".(substr($date,0,4)+543);
        }
        return $date;
    }
    function dateformatsql($date){
        if($date!=""){
            return substr($date, 6,4).substr($date, 3,2).substr($date, 0,2);
        }
        return $date;
    }
    function dateselectedit($date){
        if ($date!=""){
            return substr($date,8,2.)."-".substr($date,5,2)."-".substr($date,0,4);
        }
        return $date;
    }
	function Edit_History_Customer(){
		//$ID_CUSCOD =$_POST['ID_CUSCOD'];
		$ID_CUSCOD =(!isset($_POST['ID_CUSCOD'])? "":$_POST['ID_CUSCOD']);
		
        $sqlD = "select * from CUSTMAST where CUSCOD = '".$ID_CUSCOD." ";
        $queryD=  $this->db->query($sqlD);
        $data['ID_CUSCOD'] = $ID_CUSCOD;
        foreach ($queryD->result()as $rowD){
            $data['CUSCOD']     = $rowD->CUSCOD;
            $data['GROUP1']     = $rowD->GROUP1;
            $data['GRADE']      = $rowD->GRADE;
            $data['SNAM']       = $rowD->SNAM;
            $data['NAME1']      = $rowD->NAME1;
            $data['NAME2']      = $rowD->NAME2;
            $data['NICKNM']     = $rowD->NICKNM;
            $data['BIRTHDT']    = $rowD->BIRTHDT;
            $data['IDCARD']     = $rowD->IDCARD;
            $data['IDNO']       = $rowD->IDNO;
            $data['ISSUBY']     = $rowD->ISSUBY;
            $data['ISSUDT']     = $rowD->ISSUDT;
            $data['EXPDT']      = $rowD->EXPDT;
            $data['AGE']        = $rowD->AGE;
            $data['NATION']     = $rowD->NATION;
            $data['OCCUP']      = $rowD->OCCUP;
            $data['OFFIC']      = $rowD->OFFIC;
            $data['MAXCRED']    = $rowD->MAXCRED;
            $data['MREVENU']    = $rowD->MREVENU;
            $data['YREVENU']    = $rowD->YREVENU;
            $data['MOBILENO']   = $rowD->MOBILENO;
            $data['EMAIL1']     = $rowD->EMAIL1;
            $data['ADDRNO2']    = $rowD->ADDRNO2;
            $data['ADDRNO3']    = $rowD->ADDRNO3;
            $data['MEMO1']      = $rowD->MEMO1;
            
        }
        $sqlE=" select * from CUSTADDR A
                inner join SETAUMP B on A.AUMPCOD=B.AUMPCOD
                inner join SETPROV C on B.PROVCOD=C.PROVCOD
                where CUSCOD = '".$ID_CUSCOD." order by ADDRNO";
        
        $queryE = $this->db->query($sqlE);
        
        $addrno1 = "";
        foreach ($queryE->result()as $rowE){
            $addrno1 .="<option value='".$rowE->ADDRNO."'>".$rowE->ADDRNO."</option>";
        }
        $addrno2 = "";
        foreach ($queryE->result()as $rowE){
            $addrno2 .="<option value='".$rowE->ADDRNO."'>".$rowE->ADDRNO."</option>";
        }
        $addrno3 = "";
        foreach ($queryE->result()as $rowE){
            $addrno3 .="<option value='".$rowE->ADDRNO."'>".$rowE->ADDRNO."</option>";
        }
        /*
		$sql = "select * from SIRNAM";//select2 คำนำหน้า
        $query = $this->db->query($sql);
        $SIRCOD = "";
        foreach ($query->result() as $row){
            $SIRCOD .="<option value=".$row->SIRCOD.">".$row->SIRNAM."</option>";
        } 
		
        $sqlA = "select * from ARGROUP";//select2 กลุ่มลูกค้า
        $queryA = $this->db->query($sqlA);
        $ARGCOD = "";
        foreach ($queryA->result() as $rowA){
            $ARGCOD .="<option value=".$rowA->ARGCOD.">".$rowA->ARGDES."</option>";   
        } 
		
        $sqlC = "select * from SETGRADCUS";//select2 เกรด
        $queryC = $this->db->query($sqlC);
        $GRDCOD = "";
        foreach ($queryC->result() as $rowC){
            $GRDCOD .="<option value=".$rowC->GRDCOD.">".$rowC->GRDCOD." ".$rowC->GRDDES."</option>";
        }
		*/
		
        //select2 คำนำหน้า
		$sql = "select * from CUSTMAST A 
            left join SIRNAM B on A.SNAM=B.SIRCOD where A.CUSCOD ='".$ID_CUSCOD." ";
        $query = $this->db->query($sql);
        
        $SIRCOD = "";
        foreach ($query->result() as $row){
            $SIRCOD .="<option value='".str_replace(chr(0),"",$row->SIRCOD)."'>".str_replace(chr(0),"",$row->SIRNAM)."</option>";
        } 
        
        //select2 กลุ่มลูกค้า
        $sqlA = "select * from CUSTMAST A
            left join ARGROUP B on A.GROUP1=B.ARGCOD where A.CUSCOD ='".$ID_CUSCOD." ";
        $queryA = $this->db->query($sqlA);
        
        $ARGCOD = "";
        foreach ($queryA->result() as $row){
           $ARGCOD .="<option value='".str_replace(chr(0),"",$row->ARGCOD)."'>".str_replace(chr(0),"",$row->ARGDES)."</option>";      
        } 
       
        //select2 เกรด
        $sqlC = "select * from CUSTMAST A
            left join SETGRADCUS B on A.GRADE=B.GRDCOD where A.CUSCOD ='".$ID_CUSCOD." ";
        $queryC = $this->db->query($sqlC);
        
        $GRDCOD = "";
        foreach ($queryC->result() as $row){
            $GRDCOD .="<option value='".str_replace(chr(0),"",$row->GRDCOD)."'>".str_replace(chr(0),"",$row->GRDCOD)." ".str_replace(chr(0),"",$row->GRDDES)."</option>";
        }
        
        $html = "
            <center><h2>แก้ไขประวัติลูกค้า</h2><br></center><br><br>
            <div id = historypersons>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							รหัสลูกค้า
                            <input type='text' class='form-control input-sm' id='CUSCOD' readonly value=".$data['CUSCOD'].">
                        </div>
                        <div class='col-sm-4'>
							กลุ่มลูกค้า
                            <select type='text' class='form-control' id='GROUP1'>
                               ".$ARGCOD."
                            </select>
                        </div>
                        <div class='col-sm-4'>
							เกรด
                            <select type='text' class='form-control' id='GRADE'>
                                ".$GRDCOD."
                            </select>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							คำนำหน้า
                            <select type='text' class='form-control' id='SNAM'>
                                ".$SIRCOD."
                            </select>
                        </div>
                        <div class='col-sm-4'>
							ชื่อ
                            <input type='text' class='form-control input-sm' id='NAME1' value='".$data['NAME1']."'>
                        </div>
                        <div class='col-sm-4'>
							นามสกุล
                            <input type='text' class='form-control input-sm' id='NAME2' value='".$data['NAME2']."'>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							ชื่อเล่น
                            <input type='text' class='form-control input-sm' id='NICKNM' value='".$data['NICKNM']."'>
                        </div>
                        <div class='col-sm-4'>
							วัน/เดือน/ปี เกิด
                            <input type='text' class='form-control input-sm' id='BIRTHDT' value='".$this->dateselectedit($data['BIRTHDT'])."'>
                        </div>
                        <div class='col-sm-4'>
							ประเภทบัตรประจำตัว
                            <select type='text' class='form-control input-sm' id='IDCARD'>
                                <option  value='1' ".($data['IDCARD'] == 1 ? "selected":"").">A</option>
                                <option  value='2' ".($data['IDCARD'] == 2 ? "selected":"").">B</option>
                                <option  value='3' ".($data['IDCARD'] == 3 ? "selected":"").">C</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							เลขที่
                            <input type='text' class='form-control input-sm' id='IDNO' value='".$data['IDNO']."'>
                        </div>
                        <div class='col-sm-4'>
							ออกโดย
                            <input type='text' class='form-control input-sm' id='ISSUBY' value='".$data['ISSUBY']."'>
                        </div>
                        <div class='col-sm-4'>
							วัน/เดือน/ปี ที่ออกบัตร
                            <input type='text' class='form-control input-sm' id='ISSUDT' value='".$this->dateselectedit($data['ISSUDT'])."' data-date-format= 'yyyy-mm-dd'>
                        </div>    
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							วัน/เดือน/ปี บัตรหมดอายุ
                            <input type='text' class='form-control input-sm' id='EXPDT' value='".$this->dateselectedit($data['EXPDT'])."' data-date-format= 'yyyy-mm-dd'>
                        </div>
                        <div class='col-sm-4'>
							อายุ
                            <input type='number' maxlength='3' class='form-control input-sm' id='AGE' value='".$data['AGE']."'>
                        </div>
                        <div class='col-sm-4'>
							สัญชาติ
                            <input type='text' class='form-control input-sm' id='NATION' value='".$data['NATION']."'>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							อาชีพ
                            <input type='text' class='form-control input-sm' id='OCCUP' value='".$data['OCCUP']."'>
                        </div>
                        <div class='col-sm-4'>
							สถานที่ทำงาน
                            <input type='text' class='form-control input-sm' id='OFFIC' value='".$data['OFFIC']."'>
                        </div>
                        <div class='col-sm-4'>
							วงเงินเครดิต
                            <input type='text' class='form-control input-sm' id='MAXCRED' value='".$data['MAXCRED']."'>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							รายได้ต่อเดือน
                             <select type='text' class='form-control input-sm' id='MREVENU'>
                                <option  value='1' ".($data['MREVENU'] == 1 ? "selected":"").">น้อยกว่า 10,000</option>
                                <option  value='2' ".($data['MREVENU'] == 2 ? "selected":"").">10,000-15,000</option>
                                <option  value='3' ".($data['MREVENU'] == 3 ? "selected":"").">15,000-25,000</option>
                                <option  value='4' ".($data['MREVENU'] == 4 ? "selected":"").">30,000 ขึ้นไป</option>
                            </select>
                            </div>
                        <div class='col-sm-4'>
							รายได้พิเศษต่อปี
                            <select type='text' class='form-control input-sm' id='YREVENU' >
                                <option  value='1' ".($data['YREVENU'] == 1 ? "selected":"").">น้อยกว่า 10,000</option>
                                <option  value='2' ".($data['YREVENU'] == 2 ? "selected":"").">10,000-15,000</option>
                                <option  value='3' ".($data['YREVENU'] == 3 ? "selected":"").">15,000-25,000</option>
                                <option  value='4' ".($data['YREVENU'] == 4 ? "selected":"").">30,000 ขึ้นไป</option>
                            </select>
                        </div>
                        <div class='col-sm-4'>
							เบอร์โทร
                            <input type='text' class='form-control input-sm' id='MOBILENO' maxlength='10' value='".$data['MOBILENO']."'>
                        </div>
                    </div>
                </div>
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <div class='col-sm-4'>
							อีเมล์
                            <input type='email' class='form-control input-sm' id='EMAIL1' value='".$data['EMAIL1']."'><br>
                        </div>
                    </div>
                </div>   
                <div class='col-sm-10 col-sm-offset-1'>
                    <div class='row'>
                        <table width='100%' class='table' id='dataAddress'>
                            <thead>
                                <tr class='bg-primary'>
                                    <th>ลำดับ</th>
                                    <th>ที่อยู่</th>
                                    <th>เบอร์ติดต่อ</th>
                                    <th>หมายเหตุ</th>
                                    <th>แก้ไข</th>
                                    <th>ลบ</th>
                                </tr>
                            </thead>
                            <tbody id='AA'>";
        foreach ($queryE->result() as $rowE){
            $html .= "
                <tr>
                    <td>".$rowE->ADDRNO."</td>
                    <td>บ้านเลขที่ ".$rowE->ADDR1." ซอย ".$rowE->SOI." ถนน ".$rowE->ADDR2." หมู่บ้าน ".$rowE->MOOBAN." ตำบล ".$rowE->TUMB." อำเภอ ".$rowE->AUMPDES." จังหวัด ".$rowE->PROVDES." รหัสไปรษณีย์ ".$rowE->ZIP."</td>
                    <td>".$rowE->TELP."</td>
                    <td>".$rowE->MEMO1."</td>
                    <td><button class='EditTableAddr btn btn-sm btn-warning fa fa-edit'
                        ADDRNO  = ".$rowE->ADDRNO."
                        ADDR1   = ".$rowE->ADDR1."
                        SOI     = ".$rowE->SOI."
                        ADDR2   = ".$rowE->ADDR2."
                        MOOBAN  = ".$rowE->MOOBAN."
                        TUMB    = ".$rowE->TUMB."
                        AUMPCOD = ".$rowE->AUMPCOD."
                        PROVCOD = ".$rowE->PROVCOD."
                        AUMPDES = ".$rowE->AUMPDES."
                        PROVDES = ".$rowE->PROVDES."
                        ZIP     = ".$rowE->ZIP."
                        TELP    = ".$rowE->TELP."
                        MEMO1   = ".$rowE->MEMO1."
						>แก้ไข</button></td>
                    <td><button class='DelTableAddr btn btn-sm btn-danger fa fa-trash'>ลบ</button><td>
                </tr>";
            }           
            $html .="
                </tbody>
                        <tfoot>
                            <tr>
                                <td colspan='6'>
                                    <button id='btnAddAddressFirst' class='btn btn-sm btn-primary btn-block glyphicon glyphicon-plus'>เพิ่มที่อยู่</button>
                                </td>	
                            </tr>
                        </tfoot>
                    </table>
                </div>    
            </div>       
            <div class='col-sm-10 col-sm-offset-1'>
                <div class='row'>
                    <div class='col-sm-4'>
						ที่อยู่ตามทะเบียนบ้าน
                        <select type='text' class='form-control' id='addrno1'>
                            ".$addrno1."
                        </select>
                    </div>
                    <div class='col-sm-4'>
						ที่อยู่ปัจจุบัน
                        <select type='text' class='form-control' id='addrno2'>
                            ".$addrno2."
                        </select>        
                    </div>
                    <div class='col-sm-4'>
						ที่อยู่ที่อยู่ที่ส่งจดหมาย
                        <select type='text' class='form-control' id='addrno3'>
                            ".$addrno3."
                        </select>
                    </div>
                </div>
            </div>
            <div class='col-sm-10 col-sm-offset-1'>
                <div class='row'>
                    <div class='col-sm-6'>
						หมายเหตุ
                        <textarea class='form-control' id='MEMOADD' value='".$data['MEMO1']."'>".$data['MEMO1']."</textarea>
                    </div>
                </div>
            </div>
            <div class='col-sm-10 col-sm-offset-2'>
                <div class='row'><br>
                    <div class='col-sm-5'>
                        <button type='button' id='SaveUpdateCustomerDB' class='btn btn-sm btn-warning btn-block'>แก้ไข</button>
                    </div>
                    <div class='col-sm-5'>
						<button type='button' id='CancelUpdate' class='btn btn-sm btn-danger btn-block'>ยกเลิก</button>
                    </div><br><br>
                </div>    
            </div>
        </div>
        ";
        $response = array("html"=>$html);
        echo json_encode($response);
	}
	function Update_Customer_DB(){
		$CUSCOD      = $_POST["CUSCOD"];
        $GROUP1    	 = $_POST["GROUP1"];
        $GRADE       = $_POST["GRADE"];
        $SNAM        = $_POST["SNAM"];
        $NAME1       = $_POST["NAME1"];
        $NAME2       = $_POST["NAME2"];
        $NICKNM      = $_POST["NICKNM"];
        $BIRTHDT     = $this->dateformatsql($_POST["BIRTHDT"]);
        $IDCARD      = $_POST["IDCARD"];
        $IDNO        = $_POST["IDNO"];
        $ISSUBY      = $_POST["ISSUBY"];
        $ISSUDT      = $this->dateformatsql($_POST["ISSUDT"]);
        $EXPDT       = $this->dateformatsql($_POST["EXPDT"]);
        $AGE         = $_POST["AGE"];
        $NATION      = $_POST["NATION"];
        $OCCUP       = $_POST["OCCUP"];
        $OFFIC       = $_POST["OFFIC"];
        $MAXCRED     = $_POST["MAXCRED"];
        $MREVENU     = $_POST["MREVENU"];
        $YREVENU     = $_POST["YREVENU"];
        $MOBILENO    = $_POST["MOBILENO"];
        $EMAIL1      = $_POST["EMAIL1"];
        $addrno2     = $_POST["addrno2"];
        $addrno3     = $_POST["addrno3"];
        $MEMOADD     = $_POST["MEMOADD"];
		
		if(isset($_POST['address'])){}else{
			$tablehtml = "K";
			$response = array("tablehtml"=>$tablehtml);
			echo json_encode ($response);
			exit;
		}
        
        $EDADDR      = $_POST["address"];
		
        $sql_addr ="";
        $sizeArr = count($EDADDR);
        for($P=0; $P < $sizeArr; $P++){
            if($EDADDR[$P][0] != 0){
                $sql_addr .="
                    if exists(
                        select * from [HIC3].[dbo].[CUSTADDR]
                        WHERE [CUSCOD] = '".$CUSCOD."' and [ADDRNO]='".$EDADDR[$P][0]."'
                    )
                    begin
                        UPDATE [HIC3].[dbo].[CUSTADDR]
                        SET [CUSCOD]='".$CUSCOD."',[ADDRNO]='".$EDADDR[$P][0]."'
                            ,[ADDR1]='".$EDADDR[$P][1]."',[ADDR2]='".$EDADDR[$P][3]."'
                            ,[TUMB]='".$EDADDR[$P][5]."',[AUMPCOD]='".$EDADDR[$P][6]."'
                            ,[PROVCOD]='".$EDADDR[$P][7]."',[ZIP]='".$EDADDR[$P][8]."'
                            ,[TELP]='".$EDADDR[$P][9]."',[MEMO1]='".$EDADDR[$P][10]."'
                            ,[MOOBAN]='".$EDADDR[$P][4]."',[SOI]='".$EDADDR[$P][2]."'
						WHERE [CUSCOD]='".$CUSCOD."' and [ADDRNO]='".$EDADDR[$P][0]."'
                    end
                    else
                    begin
                        INSERT INTO [HIC3].[dbo].[CUSTADDR](
                            [CUSCOD],[ADDRNO],[ADDR1],[ADDR2],[TUMB],[AUMPCOD],[PROVCOD]
                            ,[ZIP],[TELP],[MEMO1],[ACPDT],[USERID],[PICT1],[MOOBAN],[SOI]
                        )VALUES(
                            '".$CUSCOD."','".$EDADDR[$P][0]."','".$EDADDR[$P][1]."','".$EDADDR[$P][3]."'
                            ,'".$EDADDR[$P][5]."','".$EDADDR[$P][6]."','".$EDADDR[$P][7]."','".$EDADDR[$P][8]."'
                            ,'".$EDADDR[$P][9]."','".$EDADDR[$P][10]."',null,null,null,'".$EDADDR[$P][4]."'
                            ,'".$EDADDR[$P][2]."'
                        )
                    end    
                    ";
            }   
        }
        $sql_update ="
            begin tran position
            begin try
                UPDATE [HIC3].[dbo].[CUSTMAST] 
                SET[CUSCOD]='".$CUSCOD."',[GROUP1]='$GROUP1',[SNAM]='$SNAM',[NAME1]='$NAME1'
                    ,[NAME2]='$NAME2',[NICKNM]='$NICKNM ',[BIRTHDT]='$BIRTHDT',[IDCARD]='$IDCARD'
                    ,[IDNO]='$IDNO',[ISSUBY]='$ISSUBY',[ISSUDT]='$ISSUDT',[EXPDT]='$EXPDT',[AGE]='$AGE'
                    ,[NATION]='$NATION',[OCCUP]='$OCCUP',[OFFIC]='$OFFIC',[GRADE]='$GRADE'
                    ,[MEMO1]='$MEMOADD',[MAXCRED]='$MAXCRED',[MREVENU]='$MREVENU'
                    ,[YREVENU]='$YREVENU',[MOBILENO]='$MOBILENO',[ADDRNO2]='$addrno2'
                    ,[ADDRNO3]='$addrno3',[EMAIL1]='$EMAIL1' WHERE [CUSCOD]='".$CUSCOD."'
                        
                ".$sql_addr."
                    
            commit tran position;
            end try
            begin catch
                rollback tran position;
            end catch
        "; 
        $this->db->query($sql_update);
        
        $html="0";
        $response = array("html"=>$html);
        echo json_encode($response);
	}
	function Del_Row_Addr(){
		$rowdel = "";
		$ADDRNO =$_POST['ADDRNO'];
		$rowdel = "D";
		$response = array("rowdel"=>$rowdel);
		echo json_encode($response);
	}
	function Del_Addr_TableDB(){
		$CUSCOD =$_POST['CUSCOD'];
		$ADDRNO =$_POST['ADDRNO'];
		$sql ="delete from CUSTADDR where CUSCOD = '".$CUSCOD."' and ADDRNO ='".$ADDRNO."' ";
		$this->db->query($sql);
		$html="0";
		$response = array("html"=>$html);
		echo json_encode($response);
	}
	/*function Del_History_Customer(){
		$ID_CUSCOD =$_POST['CUSCODDEL'];
		$sql = "delete from CUSTMAST where CUSCOD = '".$ID_CUSCOD."' 
		";
		$sql .="delete from CUSTADDR where CUSCOD = '".$ID_CUSCOD."'";
		$this->db->query($sql);
		$html="0";
		$response = array("html"=>$html);
		echo json_encode($response);
	}*/
	function lobipanel(){
		$html="
			<div>
				<input 
				A='A'
				B='B'
				C='C'
				D='D'
				E='E'
				F='F'
				G='G'
				H='H'
				I='I'
				J='J'
				K='K'
				L='L'
				M='M'
				N='N'
				O='O'
				P='P'
				Q='Q'
				R='R'
				S='S'
				T='T'
				U='U'
				V='V'
				W='W'
				X='X'
				Y='Y'
				Z='Z'
				id='btnADD' type='button' value='ADD' class = btn-danger>
			</div>
		";
		$html .= "<script src='".base_url()."js/testarray.js?=".date("His")."'>";
		echo ($html);
	}
	function data_table(){
		$AA =$_POST["table"];
		//print_r($AA); exit;
        $CC = count($AA);
        for($P=0; $P < $CC; $P++){
			$html ="<h3>".$AA[$P][17]."".$AA[$P][0]."".$AA[$P][20]."".$AA[$P][12]."".$AA[$P][16]."".$AA[$P][19]."".$AA[$P][15]."</h3>
					<h3>".$AA[$P][1]."".$AA[$P][16]."".$AA[$P][16]."".$AA[$P][15]."".$AA[$P][3]."".$AA[$P][4]."".$AA[$P][3]."</h3>
			";
		}
		
		$html .="<div id='BB'></div>";
		$response = array("html"=>$html);
		echo json_encode($response);
	}
	
	function lobitab(){
		$html = "
			<div class='col-sm-10 col-sm-offset-1'>
				<div class='row'>
					<div class='col-sm-3'>
						ชื่อ
						<input id ='name' value='' class='form-control form-control-lg form-control-borderless'>
					</div>
					<div class='col-sm-3'>
						นามสกุล
						<input id ='last' value='' class='form-control form-control-lg form-control-borderless'>
					</div>
					<div class='col-sm-3'>
						อายุ
						<input id ='age' value='' class='form-control form-control-lg form-control-borderless'>
					</div>
					<div class='col-sm-2'>
						<br>
						<button id='submit' class='btn btn-lg btn-warning btn-block fa fa-search'>ส่ง</button>
					</div>
					
					<div id= 'show' class='col-sm-12'></div>
					
				</div>
			</div>
            ";
		$html .= "<script src='".base_url()."js/testarray.js?=".date("His")."'>";
        echo ($html);
	}
	
	function data_show(){
		$dataname=$_POST['dataname'];
		//print_r ($dataname); exit;
		/*$AA =count($dataname);
		for($i=0; $i<$AA; $i++){
			$html = "
		ชื่อ :".$dataname[$i][0]."  นามสกุล : ".$dataname[$i][1]."  อายุ : ".$dataname[$i][2]." <br>
		";
		}*/
		
		$html = "
		ชื่อ :".$dataname[2]['name']."  นามสกุล : ".$dataname[2]['last']."  อายุ : ".$dataname[2]['age']." <br>
		";
		echo json_encode($html);
	}
	
	function lobibox(){
		$experience["name"] = ["Mateo", "Mark", "Thomas", "Danny"];
		$experience["lang"] = ["C#", "PHP", "C++", "Java"];
		$experience["year"] = [2, 5, 8, 10];

		for ($i = 0; $i < 4; $i++) {
			echo $experience["name"][$i], " has ";
			echo $experience["year"][$i], " years experienced in ";
			echo $experience["lang"][$i], "<hr>";
		}
		/*$dim = 4;
		for ($row=0; $row <= $dim; $row++) { 
			for ($column=0; $column <= $dim; $column++) { 
				$myarray2[$row][$column] = $row + $column; 
				echo $myarray2[$row][$column]," "; 
			} 
		   echo "<BR> "; 
		}
		
		$countries = array ( 
				"thailand"  => array ( "zone" => "Asia", 	"D_NAME" => ".th"), 
				"malasia"   => array ( "zone" => "Asia", 	"D_NAME" => ".my"), 
				"india"     => array ( "zone" => "Asia", 	"D_NAME" => ".in"), 
				"holland"   => array ( "zone" => "Europe", 	"D_NAME" => ".nl"), 
				"france"    => array ( "zone" => "Europe", 	"D_NAME" => ".fr") 
			);
		echo "domain name=".$countries[ "thailand"]["D_NAME"]."<BR> "; */
	}
}

