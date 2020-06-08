FROM ishaburov/laravel:latest

CMD while true; \
    do \
    php artisan schedule:run; \
    sleep 60; \
    done

