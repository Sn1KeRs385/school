<h3>Заявка в УК от </h3>
<p>{{ $order->contact_fio }}</p>
<b>Обращение</b>
<p>{{$order->description}}</p>
<div>
    <div>
        <b>Email:</b> {{ $user_data->contact_email ?? $order->creator->email}}
    </div>
    <div>
        <b>Контактный телефон:</b> {{$order->contact_phone}}
    </div>
</div>
<b>Дата и время обращения</b> {{$created_at}} (<i>по Мск</i>)
<p>Сообщение отправлено через приложение <i>Hi-Tech Dom</i></p>