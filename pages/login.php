<!DOCTYPE html>
<html lang="ru">
<head>
	<link type="image/x-icon" href="img/clipboard_1.png" rel="shortcut icon">
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<title>Планировщик задач</title>
</head>
<body>
	<div class="wrapper">
		<main class="main">
			<!-- волна -->
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
				<path fill="#ffffff" fill-opacity="1" d="M0,192L18.5,197.3C36.9,203,74,213,111,213.3C147.7,213,185,203,222,197.3C258.5,192,295,192,332,197.3C369.2,203,406,213,443,229.3C480,245,517,267,554,272C590.8,277,628,267,665,245.3C701.5,224,738,192,775,192C812.3,192,849,224,886,245.3C923.1,267,960,277,997,266.7C1033.8,256,1071,224,1108,213.3C1144.6,203,1182,213,1218,213.3C1255.4,213,1292,203,1329,208C1366.2,213,1403,235,1422,245.3L1440,256L1440,320L1421.5,320C1403.1,320,1366,320,1329,320C1292.3,320,1255,320,1218,320C1181.5,320,1145,320,1108,320C1070.8,320,1034,320,997,320C960,320,923,320,886,320C849.2,320,812,320,775,320C738.5,320,702,320,665,320C627.7,320,591,320,554,320C516.9,320,480,320,443,320C406.2,320,369,320,332,320C295.4,320,258,320,222,320C184.6,320,148,320,111,320C73.8,320,37,320,18,320L0,320Z"></path>
			</svg>
			<!-- главный блок -->
			<div class="preparation">
				<!-- первый элемент блока -->
				<div class="preparation__info">
					<!-- заголовок -->
					<h1 class="preparation__title">
						Добро пожаловать
					</h1>
					<!-- подзаголовок -->
					<h2 class="preparation__subtitle">
						Наш планировщик задач <br> с радостью поможет Вам! 
					</h2>
					<!-- картинка -->
					<img class="preparation__img" src="img/human.png" alt="">
				</div>
				<!-- второй элемент блока (формы) -->
				<form class="preparation__form">
					<!-- заголовок формы -->
					<p class="preparation__action">
						Войти
					</p>
					<!-- инпуты -->
					<input class="preparation__input" name="email" type="email" placeholder="Ваш email" required>
					<div class="notification">	
					</div>
					<input class="preparation__input" name="password" type="password" placeholder="Ваш пароль" required>
					<div class="notification">
						
					</div>
					<!-- кнопка входа в аккаунт -->
					<button class="preparation__button" type="submit">
						Войти
					</button>
					<!-- дополнительные элементы для перенаправления на регистрацию -->
					<div class="preparation__bottom">
						Нет аккаунта?
						<!-- сама ссылка, перенаправляющая на страницу регистрации -->
						<a class="preparation__link" href="register">
							Зарегистрироваться
						</a>
					</div>
				</form>
			</div>
		</main>
	</div>
	<script src="js/data.js" type="module"></script>
    <script src="js/validate.js" type="module"></script>
</body>
</html>