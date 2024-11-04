
FROM wordpress:latest

COPY ./wp-content/themes /var/www/html/wp-content/themes
COPY ./wp-content/plugins /var/www/html/wp-content/plugins
COPY ./wp-content/uploads /var/www/html/wp-content/uploads

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
