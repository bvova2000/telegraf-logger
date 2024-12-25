Этот проект предоставляет архитектуру мониторинга на основе TIG-стека (Telegraf, InfluxDB, Grafana) для отслеживания метрик следующих сервисов: MongoDB, Elasticsearch, PHP-FPM и Nginx. Проект разрабатывался с использованием Docker и описан в файле `docker-compose.yml`. Все данные сохраняются с помощью персистентных томов, чтобы не терялись после перезапуска.

### Требования:
Для запуска проекта требуется Docker версии 20.10 или выше и Docker Compose версии 1.29 или выше.

### Как запустить проект:
1. Клонируйте репозиторорий:  
   `git clone https://github.com/username/docker-monitoring-project.git`  
   `cd docker-monitoring-project`  

2. Запустите контейнеры:  
   `docker-compose up -d`  

3. Проверьте доступность сервисов:  
   - Grafana доступна по адресу `http://localhost:3000` (логин: admin, пароль: admin по умолчанию).  
   - Приложение доступно по адресу `http://localhost:8080`.  

4. Чтобы остановить проект:  
   `docker-compose down`
