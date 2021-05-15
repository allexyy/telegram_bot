#Messenger Bot

Данный бот является обработчиком сообщений из мессенджеров

Доступные мессенджеры на данный момент
* Telegram

##Запуск приложения 

Для запуска выполняем команду
```
docker-compose build && docker-compose up -d
```
Для просмотра логов используй
```
docker-compose exec php bin/console messenger:consume -vv 
```