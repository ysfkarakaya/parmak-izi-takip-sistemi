<?php
require_once 'zklibrary.php';
$zk = new ZKLibrary();
$zk->connect();


include '../../inc/db.php';

$personeller = DB::get("SELECT adsoyad,kart_id,id FROM personeller "); 
foreach ($personeller as $key => $val) {
	$adsoyad[$val->kart_id] =  $val->adsoyad;
	$personel_id[$val->kart_id] =  $val->id;
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Parmak İzi Okuyucu Yönetim Paneli</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
	<div class="container mt-5">
		<h1 class="mb-4">Parmak İzi Okuyucu Yönetim Paneli</h1>

		<!-- Cihaz Bilgileri -->
		<div class="card mb-4">
			<div class="card-header">Cihaz Bilgileri <a style="float: right;" href="javascript:void()" id="sescalButton">(Ses Çal)</a></div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6" >
						<p><strong>Versiyon:</strong> <?php echo $zk->getVersion(); ?></p>
						<p><strong>Cihaz Saati:</strong> <?php echo $zk->getTime(); ?> <a href="javascript:void()" id="adjustTimeBtn">(Saati Düzenle)</a></p>
						<p><strong>Cihaz Adı:</strong> <?php echo $zk->getDeviceName(); ?> <a href="javascript:void()" id="adcihazadiBtn">(Cihaz Adı Düzenle)</a></p>
						
					</div>
					<div class="col-md-6">
						
						<p><strong>OS Versiyonu:</strong> <?php echo $zk->getOSVersion(true); ?></p>
						<p><strong>Platform:</strong> <?php echo $zk->getPlatform(true); ?></p>
						<p><strong>Seri Numarası:</strong> <?php echo $zk->getSerialNumber(true); ?></p>
					</div>

				</div>

			</div>
		</div>

		<!-- Kullanıcılar -->
		<div class="row">
			<div class="col-md-6">
				<div class="card mb-4">
					<div class="card-header">Kullanıcılar
					<button style="float: right;margin-bottom: 0!important;" class="btn btn-success mb-3" id="addUserBtn">Kullanıcı Ekle</button>
				</div>
					<div class="card-body">
						
						<table class="table table-striped">
							<thead>
								<tr>
									<th>UID</th>
									<th>Kullanıcı ID</th>
									<th>İsim</th>
									<th>İsim2</th>
									<!-- <th>Rol</th> -->
									<th width="20%">İşlemler</th>
								</tr> 
							</thead>
							<tbody id="userTableBody">
								<?php
								$users = $zk->getUser();
								foreach ($users as $uid => $user) {
									echo "<tr>";
									echo "<td>{$uid}</td>";
									echo "<td>{$user[0]}</td>";
									echo "<td>{$user[1]}</td>";
									if ($adsoyad[$user[0]]) {
										echo "<td>{$adsoyad[$user[0]]}</td>";
									}else{
										echo '<td>-</td>';
									}

									// echo "<td>{$user[2]}</td>";
									echo "<td>
									<button class='btn btn-sm btn-warning editUserBtn' data-uid='{$uid}'>Düzenle</button>
									<button class='btn btn-sm btn-danger deleteUserBtn' data-uid='{$uid}'>Sil</button>
									</td>";
									echo "</tr>";
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card mb-4">
					<div class="card-header">Giriş Çıkış Verileri <a style="float: right;" href="javascript:void()" id="deletegiriscikis">(Temizle)</a></div>
					<div class="card-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>UID</th>
									<th>ID</th>
									<th>Durum</th>
									<th>Zaman</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$attendance = $zk->getAttendance();
								foreach ($attendance as $record) {
									echo "<tr>";
									echo "<td>{$record[0]}</td>";
									echo "<td>{$record[1]}</td>";
									echo "<td>{$record[2]}</td>";
									echo "<td>{$record[3]}</td>";
									echo "</tr>";
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Giriş Çıkış Verileri -->
		
	</div>

	<!-- Kullanıcı Ekleme/Düzenleme Modal -->
	<div class="modal fade" id="userModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="userModalLabel">Kullanıcı Ekle/Düzenle</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<form id="userForm">
						<input type="hidden" id="uid" name="uid">
						<div class="mb-3">
							<label for="userid" class="form-label">Kullanıcı ID</label>
							<input type="text" class="form-control" id="userid" name="userid" required>
						</div>
						<div class="mb-3">
							<label for="name" class="form-label">İsim</label>
							<input type="text" class="form-control" id="name" name="name" required>
						</div>
						<div class="mb-3">
							<label for="password" class="form-label">Şifre</label>
							<input type="password" class="form-control" id="password" name="password">
						</div>
						<div class="mb-3">
							<label for="role" class="form-label">Rol</label>
							<input type="number" class="form-control" id="role" name="role" min="0" max="255" required>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
					<button type="button" class="btn btn-primary" id="saveUserBtn">Kaydet</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Saat Düzenleme Modal -->
	<div class="modal fade" id="timeModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Saati Düzenle</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<form id="timeForm">
						<div class="mb-3">
							<label for="newTime" class="form-label">Yeni Saat</label>
							<input type="datetime-local" class="form-control" id="newTime" name="newTime" required>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
					<button type="button" class="btn btn-primary" id="saveTimeBtn">Kaydet</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="cihazAdiModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Cihaz Adı Düzenle Düzenle</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<form id="timeForm">
						<div class="mb-3">
							<label for="newTime" class="form-label">Cihaz Adı</label>
							<input value="<?= $zk->getDeviceName(); ?>" type="text" class="form-control" id="cihaz_adi" name="cihaz_adi" required>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
					<button type="button" class="btn btn-primary" id="savecihazadiBtn">Kaydet</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
            // Kullanıcı Ekleme
			$('#addUserBtn').click(function() {
				$('#userModalLabel').text('Kullanıcı Ekle');
				$('#userForm')[0].reset();
				$('#userModal').modal('show');
			});

            // Kullanıcı Düzenleme
			$('.editUserBtn').click(function() {
				var uid = $(this).data('uid');
				$('#userModalLabel').text('Kullanıcı Düzenle');
				$.get('islem.php?islem=get_user', {uid: uid}, function(data) {
					var user = JSON.parse(data);
					$('#uid').val(uid);
					$('#userid').val(user[0]);
					$('#name').val(user[1]);
					$('#role').val(user[2]);
				});
				$('#userModal').modal('show');
			});

            // Kullanıcı Silme
			$('.deleteUserBtn').click(function() {
				var uid = $(this).data('uid');
				if (confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')) {
					$.post('islem.php?islem=delete_user', {uid: uid}, function(data) {
						if (data.success) {
							location.reload();
						} else {
							alert('Kullanıcı silinemedi.');
						}
					}, 'json');
				}
			});	
			$('#deletegiriscikis').click(function() {
				if (confirm('Giriş Çıkış Verilerini Temizlemek İstediğinize Emin misiniz?')) {
					$.post('islem.php?islem=clear_data', function(data) {
						if (data.success) {
							location.reload();
						} else {
							alert('Veriler silinemedi.');
						}
					}, 'json');
				}
			});	
			$('#sescalButton').click(function() {
				$.post('islem.php?islem=test_voice', function(data) {
					if (data.success) {
						alert('Başarılı');

					} else {
						alert('Ses Çalınamadı');
					}
				}, 'json');
			});

            // Kullanıcı Kaydetme
			$('#saveUserBtn').click(function() {
				$.post('islem.php?islem=save_user', $('#userForm').serialize(), function(data) {
					if (data.success) {
						$('#userModal').modal('hide');
						location.reload();
					} else {
						alert('Kullanıcı kaydedilemedi.');
					}
				}, 'json');
			});
            // Saat Düzenleme
			$('#adjustTimeBtn').click(function() {
				$('#timeModal').modal('show');
			});
			$('#adcihazadiBtn').click(function() {
				$('#cihazAdiModal').modal('show');
			});

            // Saat Kaydetme
			$('#saveTimeBtn').click(function() {
				var newTime = $('#newTime').val();
				$.post('islem.php?islem=set_time', {time: newTime}, function(data) {
					if (data.success) {
						$('#timeModal').modal('hide');
						alert('Saat başarıyla güncellendi.');
					} else {
						alert('Saat güncellenemedi.');
					}
				}, 'json');
			});	
			$('#savecihazadiBtn').click(function() {
				var cihaz_adi = $('#cihaz_adi').val();
				$.post('islem.php?islem=set_devicename', {cihaz_adi: cihaz_adi}, function(data) {
					if (data.success) {
						$('#cihazAdiModal').modal('hide');
						alert('Cihaz Adı Güncelledi.');
					} else {
						alert('Cihaz Adı güncellenemedi.');
					}
				}, 'json');
			});
		});
	</script>
</body>
</html>