
<h3> Уважаемый {{ $user->lastname }} {{ $user->firstname }} {{ $user->patronymic }} !</h3>
<h4>Приветствуем Вас в личном кабинете Управляющей компании {{ $management_сompany->name }}.</h4>
<p>Ссылка для входа в Личный кабинет: <a href = "http://{{ $management_сompany->subdomain .'.'. config('app.url')  }}"> {{ $management_сompany->subdomain  .'.'. config('app.url')  }}</a> </p>
<p>Логин: {{ $user->username }} </p>
<p>Пароль: {{ $password }}</p>
<p>Вы можете изменить пароль в Личном кабинете.</p>

<p>Теперь Вы можете создать учетные записи для входа в личный кабинет для Ваших сотрудников. Ознакомьтесь с инструкцией во вложении к данному письму.</p>

<p>Мы рады, что Вы стали частью команды Hi-tech Dom!</p>

<p>С уважением, команда Hi-tech Dom</p>


