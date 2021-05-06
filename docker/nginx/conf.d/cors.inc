# @link http://nginx.org/en/docs/http/ngx_http_headers_module.html#add_header
add_header 'Access-Control-Allow-Origin' $http_origin always;
add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, PATCH, DELETE, OPTIONS' always;
add_header 'Access-Control-Allow-Credentials' 'true' always;

# Custom headers and headers various browsers *should* be OK with but aren't
add_header 'Access-Control-Allow-Headers' '*' always;

# Если надо подебажить, то можно добавить переменные в заголовки ответа
# Префикс X-Debug - хороший способ сообщить о том, что в заголовке содержатся
# именно debug данные
# add_header 'X-Debug-Auth-Endpoint' '$check_auth_upstream' always;
