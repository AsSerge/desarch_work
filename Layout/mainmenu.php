<!------------------------------------------------- Главное меню ------------------------------------------------->
<nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
		<a class="navbar-brand" href="/"><img src="/images/brand/DMT_LOGO_menu.svg" alt="ДМ Текстиль ЛОГО" ></a>
		<button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
		<span class="navbar-toggler-icon"></span>
		</button>

		<div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
		<ul class="navbar-nav mr-auto">
			<!-- <li class="nav-item active">
				<a class="nav-link" href="#">Dashboard <span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#">Уведомления</a>
			</li> -->


			<?php if($user_role == 'mgr'){?>

			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=CreativeApprovalList">На рассмотрении</a>
			</li>
			<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Постановщик задач</a>
					<div class="dropdown-menu" aria-labelledby="dropdown01">
						<a class="dropdown-item" href="/index.php?module=CreativeListView">Список креативов</a>
						<a class="dropdown-item" href="/index.php?module=TaskList">Список задач</a>
						<a class="dropdown-item" href="/index.php?module=CustomerList">Список заказчиков</a>
					</div>
			</li>


			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=HelpDesk">Помощь</a>
			</li>

			<?php } ?>

			<?php if($user_role == 'dgr'){?>
			
			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=TaskListDesigner">Список задач</a>
			</li>				
			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=CreativeList">Список креативов</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=LibraryList">Список дизайнов</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=HelpDesk">Помощь</a>
			</li>
			<?php } ?>

			<?php if($user_role == 'ctr'){?>

			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=RatingList">Список для голосования</a>
			</li>

			<?php } ?>

			<?php if($user_role == 'adm'){?>
				<li class="nav-item">
					<a class="nav-link" href="/index.php?module=LibraryList">Список дизайнов</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Администратор</a>
					<div class="dropdown-menu" aria-labelledby="dropdown01">
					<a class="dropdown-item" href="/index.php?module=UserList">Список пользователей</a>	
					<a class="dropdown-item" href="/index.php?module=UserRegistration">Регистрация пользователя</a>
					<a class="dropdown-item" href="/index.php?module=TaskList">Список задач</a>
					<a class="dropdown-item" href="/index.php?module=CustomerList">Список заказчиков</a>
					<a class="dropdown-item" href="/index.php?module=DesignerList">Список дизайнеров</a>
					</div>
				</li>

				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Работа с базой</a>
					<div class="dropdown-menu" aria-labelledby="dropdown01">
					<a class="dropdown-item" href="/Modules/SystemAdmin/clearsystem.php">Очистка базы</a>	
					</div>
				</li>


				<li class="nav-item">
					<a class="nav-link" href="/index.php?module=HelpDesk">Помощь</a>
				</li>
			<?php } ?>
			</ul>
			<ul class='navbar-nav mt-2 mt-md-0'>
				<li class="nav-item">
					<a class="nav-link" href="/Login/baselogin/logout.php"><i class="fas fa-door-open"></i> Выход</a>
				</li>
			</ul>


		</div>
</nav>
