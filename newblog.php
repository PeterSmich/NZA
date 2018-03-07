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
  $page_type = "upload";
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
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
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
			if (isset($_POST['submit'])){
				$succes = false;
				$msg = "";
				$type_b = 'callout-warning';
				$type_p = 'fa-warning';
				if($rdb_connect && $msg == ""){		
					try{
						$num = r\db('nz_database')->table('blogs')->filter(array('title'=>$_POST['title']))->count()->run($conn);
						if($num > 0){
							if($msg ==""){
								$msg = $msg.'A címnek egyedinek kell lenni!';
							}else{
								$msg = $msg.'</br>A címnek egyedinek kell lenni!';
							}
						}else{
							$result = r\db('nz_database')->table('blogs')->insert(array(
									'content' => $_POST['blogeditor'],
									'images' => explode(',',$_POST['imgs']),
									'img' => $_POST['img'],
									'language'=> $_POST['lang'],
									'sort_content' => substr($_POST['blogeditor'], 0, 45).' ...' ,
									'subtitle' => $_POST['subtitle'],
									'timestamp' => r\now(),
									'title' => $_POST['title']
								))->run($conn);
							if($result['inserted'] == 1){
								unset($_POST['submit']);
								$succes = true;
								$msg = "Sikeresen feltöltve :)";
								$type_b = 'callout-success';
								$type_p = 'fa-check';
							}else{
								$msg = implode("</br>",$result);
								$type_b = 'callout-danger';
								$type_p = 'fa-ban';
							}
						}
					}catch (Exception $e) {
						$msg = "Hiba történt az adatbázisba írás során :(".$e.'<br>';
						$type_b = 'callout-danger';
						$type_p = 'fa-ban';
					}					
				}else{
					if($msg ==""){
						$msg = $msg.'Nem tudtunk kapcsolódni az adatbázishoz :(';
					}else{
						$msg = $msg.'</br>Nem tudtunk kapcsolódni az adatbázishoz :(';
					}
					$type_b = 'callout-danger';
					$type_p = 'fa-ban';
				}
			}
		?>
		<form role = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
			<div class="row">
				<div class="col-xl-12 col-xs-12">
					<div class="box box-info">
						<div class="box-header">
							<h3 class="box-title">Alap adatok</h3>
							<!-- tools box -->
							<div class="pull-right box-tools">
								<button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							</div>
							<!-- /. tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body pad">
							<div class="col-lg-6 col-xs-12">
								<div class="form-group">
								  <label>Cím</label>
								  <input type="text" class="form-control" name="title" id="title" placeholder="" <?php if(isset($_POST['submit']) && !empty($_POST['title'])){ echo 'value="'; echo $_POST['title']; echo '"';} ?>>
								</div>
							</div>
							<div class="col-lg-6 col-xs-12">
								<div class="form-group">
								  <label>Alcím</label>
								  <input type="text" class="form-control" name="subtitle" placeholder=""  <?php if(isset($_POST['submit']) && !empty($_POST['subtitle'])){ echo 'value="'; echo $_POST['subtitle']; echo '"';} ?>>
								</div>
							</div>
							<div class="col-lg-4 col-xs-4">
								<div class="form-group">
								  <label>Borítókép</label>
								  <input type="text" class="form-control" name="img" id='img' placeholder="" <?php if(isset($_POST['submit']) && !empty($_POST['img'])){ echo 'value="'; echo $_POST['img']; echo '"';} ?>>
								</div>
							</div>
							<div class="col-lg-4 col-xs-4">
								<div class="form-group">
								  <label>Galéria képek</label>
								  <input type="text" class="form-control" name="imgs" placeholder='!Vesszővel,elválasztva!' <?php if(isset($_POST['submit']) && !empty($_POST['imgs'])){ echo 'value="'; echo $_POST['imgs']; echo '"';} ?>>
								</div>
							</div>
							<div class="col-lg-4 col-xs-4">
								<div class="form-group">
									<label>Nyelv</label>
									<select class="form-control select2" style="width: 100%;" name="lang">
										<option <?php if(isset($_POST['submit'])){if($_POST['lang'] == 'hun'){ echo 'selected="selected"';}}else{echo 'selected="selected"';} ?> >hun</option>
										<option <?php if(isset($_POST['submit']) && $_POST['lang'] == 'en'){ echo 'selected="selected"';} ?>>en</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-12 col-xs-12">
					<div class="box box-info">
						<div class="box-header">
							<h3 class="box-title">Bejegyzés</h3>
							<!-- tools box -->
							<div class="pull-right box-tools">
								<button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							</div>
							<!-- /. tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body pad" >
							<textarea id="blogeditor" name="blogeditor" rows="10" cols="80"><?php if(isset($_POST['submit']) && !empty($_POST['blogeditor'])){ echo $_POST['blogeditor'];} ?></textarea>
						</div>
					</div>
					<!-- /.box -->
				</div>
			</div>
			<button type="submit" name = "submit" class="btn btn-primary btn-block btn-flat" onclick="return validation()">Feltöltés</button>
		</form>
		<?php
			if(isset($_POST['submit']) || isset($succes)){
				if(isset($_POST['submit']) || $succes){
				$succes = false;
				unset($_POST['submit']);
				echo
				'<div class="row">
					<div class="col-xl-12 col-xs-12">
						<div class="callout '; echo $type_b; echo '">
							<h4><i class="icon fa '; echo $type_p; echo '"></i> Feltöltés:</h4>
							<p>'; echo $msg; echo '</p>
						</div>
					</div>
				</div>';
				}
			}
		?>
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
	<script>
		function validation(){
			var msg = "";
			if(!document.getElementById('title').value){
				msg = "A címről ne feledkezz meg!";
			}
			if(!document.getElementById('img').value){
				if(msg){
					msg += "</br>A képröl ne feletkezz meg!";
				}else{
					msg = "A képröl ne feletkezz meg!";
				}
			}
			if(msg){
				$('#modal-body').html(msg);
				$("#modal-warning").modal("show");
				return false;
			}
			return true;
		}
	</script>
  </div>
  <!-- /.content-wrapper -->

 <?php include("footer.php");?>