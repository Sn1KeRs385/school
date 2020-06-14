<h3>Передача показаний от квартиры {{$address}}</h3>
<b>Сообщение</b>
<p>{{$data->description}}</p>
<div>
    <div>
        <b>Email:</b> {{$data->contact_email ?? ""}}
    </div>
    <div>
        <b>Контактный телефон:</b> {{$data->contact_phone}}
    </div>
</div>
<b>Дата и время обращения</b> {{$data->created_at}} (<i>по Мск</i>)
<p>Сообщение отправлено через приложение <i>Hi-Tech Dom</i></p>