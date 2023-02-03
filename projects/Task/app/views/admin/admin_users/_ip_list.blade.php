@foreach ($ips as $key => $ip)
    @include('admin.admin_users._ip', ['ip' => $ip, 'key' => $key])
@endforeach