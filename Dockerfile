FROM richarvey/nginx-php-fpm:1.9.1

# Cau hinh timezone +7
RUN apk add tzdata
RUN cp /usr/share/zoneinfo/Asia/Ho_Chi_Minh /etc/localtime
RUN echo "Asia/Ho_Chi_Minh" > /etc/timezone

# Create source folder & Copy it
RUN mkdir -p /var/www/html/web
COPY . /var/www/html/web
RUN mv /var/www/html/web/devops/vendor /var/www/html/web/vendor
RUN cd /var/www/html/web && php composer.phar dump-autoload

# Copy vhost config
COPY ./nginx-site.conf /etc/nginx/sites-available/default.conf
COPY ./nginx-mime.types /etc/nginx/mime.types
COPY ./suppervisord.conf /etc/supervisor/conf.d/init-service.conf

# Get connection string when container start
#RUN php /var/www/html/web/artisan epoint:connection_string
RUN chmod -R 777 /var/www/html/web/bootstrap/cache
RUN chmod -R 777 /var/www/html/web/storage/framework/*
RUN chmod -R 777 /var/www/html/web/storage/framework/cache
RUN chmod -R 777 /var/www/html/web/storage/framework/views
RUN chmod -R 777 /var/www/html/web/storage/framework/sessions
RUN chmod -R 777 /var/www/html/web/storage/logs

# Create the log file to be able to run tail
RUN echo '' > /root/project_env.sh
RUN echo '*  *  *  *  *    cd /var/www/html/web && php artisan schedule:run >> /var/log/cron.log 2>&1' > /etc/crontabs/root
# Get conne

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

