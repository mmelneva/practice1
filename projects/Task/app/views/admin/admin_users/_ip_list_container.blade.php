<fieldset class="bordered-group form-group manage-ip-container">
    <legend>Разрешённые IP-адреса</legend>

    {{ Form::tbFormGroupOpen('allowed_ips') }}
        {{ Form::tbLabel('allowed_ips', 'IP-адрес') }}
        <div class="ips-container">
            @include('admin.admin_users._ip_list', ['ips' => $ips])
        </div>
        <div class="add-new">
            <a href="#">Добавить IP-адрес</a>
            <a href="#" id="add-my-ip"
               data-myip="{{{ Request::getClientIp() }}}">Добавить текуший IP-адрес ({{{ Request::getClientIp() }}})</a>
            <div class="add-container">
                @include('admin.admin_users._ip', ['ip' => '', 'key' => ''])
            </div>
        </div>
    {{ Form::tbFormGroupClose() }}
</fieldset>