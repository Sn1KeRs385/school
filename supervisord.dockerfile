FROM ishaburov/laravel:latest

RUN apk update && apk add supervisor \
    ffmpeg \
    && mkdir /home/supervisord

ENTRYPOINT ["supervisord", "--nodaemon", "--configuration", "/home/supervisord/supervisord.conf"]
