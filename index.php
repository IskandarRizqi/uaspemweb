<?php
	include "config.php";
?>
<html>
	<head>
		<title>CRUD DATA SURAT MASUK</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		<!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
		<link href="style.css" rel="stylesheet" type="text/css" media="all" />
	</head>
	<body>
		
		<!-- JUDUL APLIKASI -->
	<div class="container">
        <div class="row my-2">
            <div class="col-md">
                <h3 class="text-center fw-bold text-uppercase">CRUD DATA SURAT MASUK</h3>
                <hr>
            </div>
        </div>

		<!-- MEMBUAT FORM TABEL INPUT DATA -->
		<div class='row'>
			<div class="col-md-5">
				<form id='frm'>
				  <div class="form-group">
					<label>Nomor</label>
					<input type="nomor" class="form-control" name="nomor" id='nomor' required placeholder="Enter nomor">
				  </div>
				  <div class="form-group">
					<label>tanggal surat</label>
					<input type="date" class="form-control" name="tanggal" id='tanggal' required placeholder="Enter tanggal">
				  </div>
				  <div class="form-group">
					<label>Pengirim</label>
					<input type="pengirim" class="form-control"  name="pengirim" id='pengirim' required placeholder="Enter pengirim">
				  </div>
				  
				  <input type="hidden" class="form-control" name="uid" id='uid' required value='0' placeholder="">
				  
				  <button type="submit" name="submit" id="but" class="btn btn-success">TAMBAH DATA</button>
				  <button type="button" id="clear" class="btn btn-warning">Reset</button>
				</form> 
			</div>

			<!-- MEMBUAT TABEL DATA -->
			<div class="col-md-7">
				<table class="table table-bordered" id='table'>
					<thead>
						<tr>
							<th>nomor</th>
							<th>tanggal surat</th>
							<th>pengirim</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
					</thead>

					<!-- VALIDASI DATA TABEL DENGAN TABEL DI DB  -->
					<tbody>
						<?php
							$sql="select * from user";
							$res=$con->query($sql);
							if($res->num_rows>0)
							{
								while($row=$res->fetch_assoc())
								{	
									echo"<tr class='{$row["UID"]}'>
										<td>{$row["NOMOR"]}</td>
										<td>{$row["TANGGAL"]}</td>
										<td>{$row["PENGIRIM"]}</td>
										<td><a href='#' class='btn btn-primary edit' uid='{$row["UID"]}'>Edit</a></td>
										<td><a href='#' class='btn btn-danger del' uid='{$row["UID"]}'>Delete</a></td>					
									</tr>";
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>	
	<script>
		$(document).ready(function(){
			
			//RESET INPUTAN DATA PADA MENU EDIT
			$("#clear").click(function(){
				$("#nomor").val("");
				$("#tanggal").val("");
				$("#pengirim").val("");
				$("#uid").val("0");
				$("#but").text("Tambah Data");
			});
			
			//MENGUPDATE DATA 
			$("#but").click(function(e){
				e.preventDefault();
				var btn=$(this);
				var uid=$("#uid").val();
				
				
				var required=true;
				$("#frm").find("[required]").each(function(){
					if($(this).val()==""){
						alert($(this).attr("placeholder"));
						$(this).focus();
						required=false;
						return false;
					}
				});
				if(required){
					$.ajax({
						type:'POST',
						url:'ajax_action.php',
						data:$("#frm").serialize(),
						beforeSend:function(){
							$(btn).text("Wait...");
						},
						success:function(res){
							
							var uid=$("#uid").val();
							if(uid=="0"){
								$("#table").find("tbody").append(res);
							}else{
								$("#table").find("."+uid).html(res);
							}
							
							$("#clear").click();
							$("#but").text("Tambah Data");
						}
					});
				}
			});
			
			//MENGHAPUS DATA
			$("body").on("click",".del",function(e){
				e.preventDefault();
				var uid=$(this).attr("uid");
				var btn=$(this);
				if(confirm("Are You Sure ? ")){
					$.ajax({
						type:'POST',
						url:'ajax_delete.php',
						data:{id:uid},
						beforeSend:function(){
							$(btn).text("Deleting...");
						},
						success:function(res){
							if(res){
								btn.closest("tr").remove();
							}
						}
					});
				}
			});
			
			//mengisi form input data ssesuai tabel db
			$("body").on("click",".edit",function(e){
				e.preventDefault();
				var uid=$(this).attr("uid");
				$("#uid").val(uid);
				var row=$(this);
				var nomor=row.closest("tr").find("td:eq(0)").text();
				$("#nomor").val(nomor);
				var tanggal=row.closest("tr").find("td:eq(1)").text();
				$("#tanggal").val(tanggal);
				var pengirim=row.closest("tr").find("td:eq(2)").text();
				$("#pengirim").val(pengirim);
				$("#but").text("Update User");
			});
		});
	</script>
	<div class="container-fluid">
        <div class="row bg-dark text-white">
            <div class="col-md-6 my-2" id="about">
                <h4 class="fw-bold text-uppercase">About</h4>
                <p>Politeknik Harapan Bersama Tegal, Program Studi Teknik Informatika</p>
            </div>
            <div class="col-md-6 my-2 text-center link">
                <h4 class="fw-bold text-uppercase">Social Media</h4>
                <a href="#" target="_blank"><i class="bi bi-facebook fs-3"></i></a>
                <a href="https://github.com/IskandarRizqi" target="_blank"><i class="bi bi-github fs-3"></i></a>
                <a href="#" target="_blank"><i class="bi bi-instagram fs-3"></i></a>
                <a href="#" target="_blank"><i class="bi bi-twitter fs-3"></i></a>
            </div>
        </div>
    </div>
    <footer class="bg-dark text-white text-center" style="padding: 5px;">
        <p>Created with <i class="bi bi-suit-heart-fill" style="color: white;"></i> By Iskandar Rizqi</p>
    </footer>
    <!-- Close Footer -->
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
	</body>
</html>