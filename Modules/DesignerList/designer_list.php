<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-drafting-compass" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Список дизайнеров</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>
<style>
.dash_item{
	border: 1px solid var(--info);
	font-size: 0.8rem;
}
.dash_item__head{
	background-color: var(--info);
	color: white;
	padding: 0.5rem 0.8rem;
}
.dash_item__body{
	padding:0.5rem;
}
</style>


<div class="my-3 p-3 bg-white rounded box-shadow">

<?php
// Список всех дизайнеров
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_role = 'dgr'");
$stmt->execute();
$designers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем общее принятых количество креативов
function CheckDesignerCreatives($pdo, $user_id){
	$stmt = $pdo->prepare("SELECT * FROM сreatives WHERE user_id = ? AND creative_status = 'Принят'");
	$stmt->execute(array($user_id));
	return $stmt->rowCount();
}
// Получаем общее число задач по дизайнеру
?>
	<div class="row dash_board">
		<div class="col-md-3">
			<div class="dash_item rounded box-shadow">
				<div class="dash_item__head">
				<i class="fas fa-user-graduate"></i> Дизайнеры	
				</div>
				<div class="dash_item__body">
				<table class="table table-sm">
				<?php
					foreach($designers as $dgr){
						$creativeCount = CheckDesignerCreatives($pdo, $dgr['user_id']); // Число креативов
						echo "<tr>";
						echo "<td>{$dgr['user_id']}</td>";
						echo "<td>{$dgr['user_name']} {$dgr['user_surname']}</td>";
						echo "<td></td>";
						echo "<td>{$creativeCount}</td>";
						echo "</tr>";
					}
					?>
					</table>
				</div>	
			</div>
		</div>
	</div>

</div>