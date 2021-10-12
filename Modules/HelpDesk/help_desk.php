<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="far fa-question-circle" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Служба поддержки</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>

<style>
	.MyDescription td:first-child{
		font-weight: 600;
	}
	.MyStrong{
		font-weight: 600;
	}
	.HelpDeskImage{
	}
</style>


<div class="my-3 p-3 bg-white rounded box-shadow">
	<div class="row">
		<div class="col-md-3">
			<h5 class="border-bottom border-gray pb-3 mb-2"><i class="far fa-images"></i> Модули системы</h5>
			<div class="m-3 MyModuleHref">

				<div class="m-3">
					<a href="#" class="OneDot" data-source="hd_customers.php">Список заказчиков</a>
				</div>
				<div class="m-3">
					<a href="#" class="OneDot" data-source="hd_tasks.php">Список задач</a>
				</div>
				<div class="m-3">
					<a href="#" class="OneDot" data-source="hd_сreatives.php">Список креативов</a>
				</div>
				<div class="m-3">
					<a href="#" class="OneDot" data-source="hd_сreative_grades.php">Разрешение на покупку</a>
				</div>
				<div class="m-3">
					<a href="#" class="OneDot" data-source="hd_file_formats.php">Форматы файлов</a>
				</div>
			</div>
		</div>
		<div class="col-md-9" id="HelpDeskContent">
		</div>	
	</div>

</div>
