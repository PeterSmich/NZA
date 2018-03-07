<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<?php
  session_start();
  if(!isset($_SESSION['valid'])){
	$_SESSION['valid'] = false;
  }
  $_SESSION['redirect_url'] = "newblog.php";
  if( $_SESSION['valid'] == true){
  }else{
	$_SESSION['nickname'] = 'Anonymus';
	header("Location: login.php");
  }
  $page_type = "img";
  // Load the driver
  require_once("dist/rdb/rdb.php");
  // Connect to localhost
  try{
	$conn = r\connect('localhost');
	$rdb_connect = true;
  }catch (Exception $e){
	  $rdb_connect = false;
  }
?>

<?php include("header.php");?>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS			| skin-blue								  |
|				| skin-black							  |
|				| skin-purple							  |
|				| skin-yellow							  |
|				| skin-red								  |
|				| skin-green							  |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed									  |
|				| layout-boxed							  |
|				| layout-top-nav						  |
|				| sidebar-collapse						  |
|				| sidebar-mini							  |
|---------------------------------------------------------|
-->
<?php include("navigation.php");?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Content Header (Page header) - ->
	<section class="content-header">
	  <h1>
		Page Header
		<small>Optional description</small>
	  </h1>
	  <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
		<li class="active">Here</li>
	  </ol>
	</section>-->

	<!-- Main content -->
	<section class="content container-fluid">

	  <!--------------------------
		| Your Page Content Here |
		-------------------------->
		<?php
			if($rdb_connect){
				$tags = r\db('nz_database')->table('tags')->run($conn);
			}
		?>

		<form role = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post" enctype="multipart/form-data" >
			<div class="row">
				<div class="col-md-12 col-xs-12 ">
					<input type="file" name="uploadFiles[]" accept="image/*" id="file-5" class="inputfile inputfile-4" data-multiple-caption="{count} files selected" multiple onchange="showImgs(event)" style="display: none;"/>
					<label for="file-5"><figure><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></figure> <span>Choose a file&hellip;</span></label>
				</div>
			</div>
			<div class="row" id="mainOPT" style="display: none;">
				<div class="col-md-6 col-xs-12 ">
					<div class="form-group">
						Global TAG:
						<input type="text" class="form-control" name="globalTAG[]" id="globalTAG" placeholder="Global Tag">
					</div>
				</div>
				<div class="col-md-6 col-xs-12 ">
					<div class="form-group">
						<label>TAGek:</label>
						<select class="form-control select2" multiple="multiple" id="mainTAGs" data-placeholder="TAGs" style="width: 100%;" >
						<?php
							if(isset($tags)){
								$tmp = array();
								foreach($tags as $tag){
									echo'<option value="'.$tag['tag'].'">'.$tag['tag'].'</option>';
									array_push($tmp,$tag['tag']);
								}
								$jsont = json_encode($tmp);
							}
						?>
						</select>
					</div>
				</div>
				<div style="display:none;">
					<div class="form-group">
						<label>Egyedi global TAG:<input type="checkbox" class="flat-red" name="globalTAGnew[]" id="globalTAGnew" value="1" checked></label>
					</div>
					<div class="form-group">
						<label>Egyedi TAG:<input type="checkbox" class="flat-red" name="TAGsnew[]" id="TAGsnew" value="1" checked></label>
						<label>Fő TAGek megtartása:<input type="checkbox" class="flat-red" name="mainTAGsorig[]" id="mainTAGsorig" value="1" checked></label>
					</div>
					<input type="hidden" name="cerdate[]" value="DATE" />
					<input type="hidden" name="imgname[]" id="imgname" value="" />
					<input type="hidden" name="description[]" id="description" value="description">
					<input type="hidden" name="mainTAGs[]" id="mainTAGsTEXT" value="mainTAGsTEXT">
				</div>
			</div>
			<div id="img_container">
				<!--Ide kerül a form dinamikus része-->
			</div>
			<div class="row">
				<div class="col-md-12 col-xs-12 " id="upload_btn" style="display: none;">
					<button type="submit" name = "submit" class="btn btn-primary btn-block btn-flat" onclick="return validation()">Feltöltés</button>
				</div>
			</div>
			<?php
				if (isset($_POST['submit'])){
					if($rdb_connect){
						$errors = array();
						for ($i = 0; $i < count($_FILES['uploadFiles']['name']); $i++) {
							$j = $i+1;
							$msg = "";
							if ($_FILES["uploadFiles"]["size"][$i] < 2097152000) {
								// for($k = 1; $k < count($_POST['globalTAGnew']); $k++){
									// if($_POST['globalTAGnew'][$k] == $_POST['imgname'][$j]){
										// $global = $_POST['globalTAG'][$j];
										// break;
									// }
								// }
								// if(!isset($global)){
									// $global = $_POST['globalTAG'][0];
								// }
								
								// $tags = array();
								// for($k = 1; $k < count($_POST['TAGsnew']); $k++){
									// if($_POST['TAGsnew'][$k] == $_POST['imgname'][$j]){
										// array_push($tags,$_POST['mainTAGs'][$j]);
									// }
								// }
								// for($l = 1; $l < count($_POST['mainTAGsorig']); $l++){
									// if($_POST['mainTAGsorig'][$k] == $_POST['imgname'][$j]){
										// array_push($tags,$_POST['mainTAGs'][0]);
									// }
								// }
								// if(empty($tags)){
									// array_push($tags,$_POST['mainTAGs'][0]);
								// }
								
								// if(!isset($_POST['description'][$j])){
									// $description = "";
								// }else{
									// $description = $_POST['description'][$j];
								// }
								$result = r\db('nz_database')->table('images')->insert(array('name'=>$_POST['imgname'][$j],'timestamp'=>r\epochTime((int)$_POST['cerdate'][$j]),'globaltag'=>$_POST['globalTAG'][$j],'tags'=>explode(",",$_POST['mainTAGs'][$j]), 'description'=>$_POST['description'][$j]))->run($conn);
								if($result['inserted'] == 1 && isset($result['generated_keys'])){
									try{
										$new_path = dirname(__FILE__).'\\dist\\img\\';
										$ext = explode("/", $_FILES["uploadFiles"]["type"][$i]);
										$target_path = $new_path.$result['generated_keys'][0].'.'.end($ext);
									if (!move_uploaded_file($_FILES['uploadFiles']['tmp_name'][$i], $target_path)) {
										$result = r\db('nz_database')->table('images')->get($result['generated_keys'])->delete()->run($conn);
										$msg = 'Hiba a php feltöltés során';
									}
									}catch (Exception $e) {
										$msg = 'Hiba a php feltöltés során:</br>'.$e->getMessage();
									}
								}else{
									if($result['error'] > 0){
										$msg = 'Hiba az adatbázisba írás során'.$result['first_error'];
									}else{
										$msg = 'Hiba az adatbázisba írás során';
									}
								}									
							}else{
								$msg = "Túl nagy a file! (max: 2MB)";
							}
							array_push($errors,$msg);
						}
						$out = '';
						for($i = 0; $i < count($_FILES['uploadFiles']['name']); $i++){
							$j = $i+1;
							if($errors[$i] != ""){
								if($out == ''){
									$out = $out.'
								<div class="row">';
								}
								$out = $out.'
									<div class="box box-default col-xl-12 col-xs-12">
										<div class="box-header with-border">
											<h3 class="box-title">
												<i class="fa fa-tag"></i>'.$_POST['imgname'][$j].'
											</h3>
										</div>
										<div class="box-body">
											<div class="row">
												<div class="col-md-2 col-xs-12" id="img_'.$j.'">
													<img class="img-responsive pad img-thumbnail" id="imgreal_'.$i.'" src="'.$_FILES['uploadFiles']['tmp_name'][$i].'" alt="'.$_POST['imgname'][$j].'">
												</div>
												<div class="col-md-10 col-xs-12" id="img_'.$i.'">
													<div class="callout callout-danger">
														<h4>Error:!</h4>
														<p>'.$errors[$i].'</p>
													</div>
												</div>
											</div>
										</div>
									</div>';
							}
						}
						if($out != ''){
							$out = $out.'
								</div >';
						}
					}
				}
			?>
		</form>
		<div class="modal modal-warning fade" id="modal-warning">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Figyelem</h4>
			  </div>
			  <div class="modal-body">
				<p id="modal-body">&hellip;</p>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Rendben</button>
			  </div>
			</div>
			<!-- /.modal-content -->
		  </div>
		  <!-- /.modal-dialog -->
		</div>
	</section>
  </div>
  
  <!-- /.content-wrapper -->
  <script src="dist/js/custom-file-input.js"></script>
	<script>
		
		function nextImg(num, name, cerdate){
			var tags = <?php if($jsont==''){echo'[]';}else{echo $jsont; }?>;
			var out = '<div class="row"><div class="box box-default col-xl-12 col-xs-12"><div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i>'+name+'</h3></div><div class="box-body"><div class="row"><div class="col-md-2 col-xs-12" id="img_'+num+'"><img class="img-responsive pad img-thumbnail" id="imgreal_'+num+'" src="#" alt="'+name+'"></div><div class="col-md-2 col-xs-12"><div class="form-group"><label>Egyedi global TAG:<input type="checkbox" class="flat-red" name="globalTAGnew[]" id="globalTAGnew'+num+'" value="'+name+'"></label></div><div class="form-group"><label>Egyedi TAG:<input type="checkbox" class="flat-red" name="TAGsnew[]" id="TAGsnew'+num+'" value="'+name+'"></label><label>Fő TAGek megtartása:<input type="checkbox" class="flat-red" checked name="mainTAGsorig[]" id="mainTAGsorig'+num+'" value="'+name+'" ></label></div></div><div class="col-md-8 col-xs-12"><div class="form-group"><input type="text" class="form-control" name="globalTAG[]" id="globalTAG'+num+'" placeholder="Global Tag"></div><div class="form-group"><select class="form-control select2" multiple="multiple" id="mainTAGs'+num+'" data-placeholder="TAGs" style="width:100%;" >';
			for(var j =	 0; j < tags.length; j++){
				out += '<option>'+tags[j]+'</option>';
			}
			out += '</select><input type="hidden" name="cerdate[]" value="'+cerdate+'" /><input type="hidden" name="imgname[]" id="imgname'+num+'" value="'+name+'" /></div></div></div><div class="row"><div class="col-md-12 col-xs-12"><div class="form-group"><input type="text" class="form-control" name="description[]" id="description'+num+'" placeholder="Leírás"></div></div><input type="hidden" name="mainTAGs[]" id="mainTAGsTEXT'+num+'" value=""></div></div></div></div>';
			return out;
		}
					
		function showImgs(event){
			var files = event.target.files;
			files_length = files['length'];
			console.log(files);
			if(files['length'] > 0){
				document.getElementById('mainOPT').style.display = "block";
				document.getElementById('upload_btn').style.display = "block";
				var div_edit = ""
				for(var i = 0; i < files['length']; i++){
					div_edit += nextImg(i,files[i]['name'],files[i]['lastModified']);
				}
				document.getElementById("img_container").innerHTML = div_edit;
				
				for(var i = 0; i < files['length']; i++){
					(function(n) {
						var reader = new FileReader();
						reader.onload = function (e) {
							var num = "imgreal_"+n;
							var output = document.getElementById(num);
							output.src = reader.result;
						}
						reader.readAsDataURL(event.target.files[n]);
					})(i);
				}
			}else{
				document.getElementById('mainOPT').style.display = "none";
				document.getElementById('upload_btn').style.display = "none";
				document.getElementById("img_container").innerHTML = "";
			}
			
			$('.select2').select2()
			$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
			  checkboxClass: 'icheckbox_flat-green',
			  radioClass   : 'iradio_flat-green'
			})
		}
		
		function getSelectValues(select) {
			var result = [];
			var options = select && select.options;
			var opt;

			for (var i=0, iLen=options.length; i<iLen; i++) {
				opt = options[i];

				if (opt.selected) {
					result.push(opt.value || opt.text);
				}
			}
			return result;
		}

		function validation(){
			var msg = "";
			if(!document.getElementById('globalTAG').value){
				msg = "A FŐ Global TAGről ne feletkezz meg!";
			}
			if(!document.getElementById('mainTAGs').value){
				if(msg){
					msg += "</br>Legalább 1 TAGet adj meg!";
				}else{
					msg = "Legalább 1 TAGet adj meg!";
				}
			}
			for(var i = 0; i < files_length; i++){
				if(document.getElementById('TAGsnew'+i).checked){
					if(!document.getElementById('mainTAGs'+i).value){
						if(msg){
							msg += "</br>A kép ("+document.getElementById('imgname'+i).value+") további TAGeit ne feledd!";
						}else{
							msg = "A kép ("+document.getElementById('imgname'+i).value+") további TAGeit ne feledd!";
						}
					}
				}
				if(document.getElementById('globalTAGnew'+i).checked){
					if(!document.getElementById('globalTAG'+i).value){
						if(msg){
							msg += "</br>A kép ("+document.getElementById('imgname'+i).value+") Global TAGét ne feledd!";
						}else{
							msg = "A kép ("+document.getElementById('imgname'+i).value+") Global TAGét ne feledd!";
						}
					}
				}
			}
			if(msg){
				$('#modal-body').html(msg);
				$("#modal-warning").modal("show");
				return false;
			}
			try{
			for(var i = 0; i < files_length; i++){
				console.log(i);
				if(!document.getElementById('globalTAGnew'+i).checked){
					document.getElementById('globalTAG'+i).value = document.getElementById('globalTAG').value;
					console.log('!newGlobal');
				}
				var a = getSelectValues(document.getElementById('mainTAGs'+i)) ;
				var b = getSelectValues(document.getElementById('mainTAGs'));
				if(document.getElementById('TAGsnew'+i).checked){
					if(document.getElementById('mainTAGsorig'+i).checked){
						for (var j = 0; j < b.length; j++){
							if(!a.includes(b[j])){
								a.push(b[j]);
							}
						}
						console.log('new+orig');
						document.getElementById('mainTAGsTEXT'+i).value = a.toString();
					}else{
						console.log('new');
						document.getElementById('mainTAGsTEXT'+i).value = a.toString();
					}
				}else{
					console.log('orig');
					document.getElementById('mainTAGsTEXT'+i).value = b.toString();
				}
				if(!document.getElementById('description'+i).value){
					document.getElementById('description'+i).value = " ";
				}
			}
			}catch(err){console.log(err.message); return false;}
			return true;
		}
	</script>
 <?php include("footer.php");?>