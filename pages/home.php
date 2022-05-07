<!DOCTYPE html>
<html lang="ru">
<head>
<link type="image/x-icon" href="img/clipboard_1.png" rel="shortcut icon">
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/datepicker.minimal.css">
	<link rel="stylesheet" href="css/main.css">
	<title>TASKDLY</title>
</head>
<body>
	<div class="wrapper">
		<!-- секция header -->
		<header class="header">
			<div class="container">
				<!-- лого -->
				<div class="logo">
					TASKDLY
				</div>
			</div>
		</header>
		<!-- секция main -->
		<main class="main">
			<!-- секция search -->
			<section class="search">
				<div class="container">
					<!-- форма поиска -->
					<form action="#" class="search__form">
						<input class="search__action" type="text" placeholder="Поиск" required>
						<button class="search__button">Поиск</button>
					</form>
				</div>
			</section>
			<!-- секция tasks -->
			<section class="tasks">
				<div class="container">
					<!-- заголовок секции -->
					<div class="tasks__title">
						Ваши задачи
						<span class="tasks__data">
							<?= date('d.m.y', $_SESSION['user']['timestamp']); ?>
						</span>
					</div>
					<!-- пустой блок, который будет по дефолту или тогда, когда нет ни одной задачи -->
					<div class="tasks__null disabled">
						Здесь будут отображаться Ваши задачи
					</div>
					<!-- форма с задачами -->
					<div class="tasks__form">
						
						<!-- <div class="tasks__item active">
							<div class="tasks__options">
								<button class="tasks__btn tasks__btn-complete"></button>
								<button class="tasks__btn tasks__btn-delete"></button>
							</div>
							<div class="tasks__result">
								<textarea class="tasks__textarea" name="" id="" readonly></textarea>
								<div class="tasks__change">
									<button class="tasks__btn-change">Изменить</button>
								</div>
								<div class="tasks__change mobile">
									<button class="tasks__btn-change change-mobile">
									</button>
								</div>
							</div>
						</div> -->
						
					</div>
				</div>
			</section>
    <!-- pop-up add task -->
	<!-- сам бекграунд -->
    <div class="popup__background" id="add-task">
		<!-- само окно попапа -->
        <form class="popup" action="#">
            <img src="/img/close.svg" alt="" class="popup__close">
			<!-- заголовок попапа -->
            <div class="popup__title">
                Добавить новую задачу?
            </div>
			<!-- блок для выравнивания флекс элементов в попапе -->
            <div class="popup__wrapper-padding">
                <textarea id="add-textarea" class="popup__textarea" placeholder="Напишите здесь что-нибудь" name=""></textarea>
                <div id="add-notification" class="notification"></div>
				<button id="add-confirm" class="popup__button" type="submit">Добавить</button>
            </div>
        </form>
    </div>
    <!-- pop-up change task -->
    <div class="popup__background" id="change-task">
        <form class="popup" action="#">
            <img src="/img/close.svg" alt="" class="popup__close">
            <div class="popup__title">
                Изменить задачу
            </div>
            <div class="popup__wrapper-padding">
                <textarea id="change-textarea" class="popup__textarea" placeholder="Напишите здесь что-нибудь" name=""></textarea>
				<!-- уведомление -->
				<div id="change-notification" class="notification"></div>
                <button id="change-confirm" class="popup__button" type="submit">Применить</button>
            </div>
        </form>
    </div>
    <!-- pop-up logout profile -->
    <div class="popup__background" id="logout-task">
        <form class="popup" action="#">
            <img src="/img/close.svg" alt="" class="popup__close">
            <div class="popup__title">
                Уже уходите? :(
            </div>
            <div class="popup__wrapper-padding">
                <button id="logout-confirm" class="popup__button" type="submit">Да</button>
                <button id="logout-refuse" class="popup__button" type="submit">Нет</button>
            </div>
        </form>
    </div>
    <!-- pop-up delete task -->
    <div class="popup__background" id="delete-task">
        <form class="popup" action="#">
            <img src="/img/close.svg" alt="" class="popup__close">
            <div class="popup__title">
                Удалить задачу?
            </div>
            <div class="popup__wrapper-padding">
                <button id="delete-confirm" class="popup__button" type="submit">Да</button>
                <button id="delete-refuse" class="popup__button" type="submit">Нет</button>
            </div>
        </form>
    </div>
	<!-- pop-up calendar -->
	<div class="popup__background" id="calendar-task">
        <form class="popup" action="#">
            <img src="/img/close.svg" alt="" class="popup__close">
            <div class="popup__title">
                Календарь
            </div>
            <div class="popup__wrapper-padding">
				<input id="calendar-select" type="hidden" readonly>
                <button id="calendar-confirm" class="popup__button" type="submit">Выбрать</button>
            </div>
        </form>
    </div>
		</main>
		<!-- основное меню -->
        <div class="right-menu">
            <button class="right-menu__button add-btn">
				<img src="/img/plus.svg" alt="">
            </button>
            <button class="right-menu__button calendar-btn">
				<img src="/img/calendar.svg" alt="">
            </button>
            <button class="right-menu__button logout-btn">
				<img src="/img/exit.svg" alt="">
            </button>
        </div>
		<!-- меню для планшетов телефонов -->
        <div class="bottom-menu">
            <button class="bottom-menu__button add-btn">
				<img src="/img/plus.svg" alt="">
            </button>
            <button class="bottom-menu__button calendar-btn">
				<img src="/img/calendar.svg" alt="">
            </button>
            <button class="bottom-menu__button logout-btn">
				<img src="/img/exit.svg" alt="">
            </button>
        </div>
		<!-- секция footer -->
		<footer class="footer">
			<div class="container">
				<a class="footer__link" href="https://github.com/globalwa/taskdly" target="blank">
					<img  class="footer__link-img" src="img/github_white.svg" alt="">
				</a>
				<!-- лого -->
				<div class="logo">
					TASKDLY
				</div>
			</div>
		</footer>
	</div>
	<script src="js/datepicker.js"></script>
	<script src="js/data.js" type="module"></script>
	<script src="js/popup.js" type="module"></script>
	<script src="js/tasks.js" type="module"></script>
	<script src="js/textarea_size.js"></script>
</body>
</html>