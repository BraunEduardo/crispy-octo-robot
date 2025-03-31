FROM ubuntu

# Debconf não interativo
ARG DEBIAN_FRONTEND="noninteractive"

# Configurações de ambiente
ENV LANG=pt_BR.UTF-8
ENV LC_ALL=pt_BR.UTF-8
ENV LANGUAGE=pt_BR.UTF-8

RUN apt-get update && apt-get install -y locales tzdata\
 && sed -i '/^# *pt_BR.UTF-8/s/^# //' /etc/locale.gen\
 && locale-gen pt_BR.UTF-8\
 && update-locale LANG=pt_BR.UTF-8\
 && ln -fs /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime\
 && dpkg-reconfigure -f --noninteractive tzdata

# Pacotes essenciais
 RUN apt update && apt install -y\
 build-essential\
 cmake\
 software-properties-common\
 ca-certificates\
 curl\
 zip\
 unzip

# nginx e PHP
RUN apt update && apt install -y\
 nginx\
 php-mysql\
 php-gd\
 php-curl\
 php-mbstring\
 php-zip\
 php-common\
 php-readline\
 php-intl\
 php-xml\
 php-fpm

# Composer
COPY --chmod=755 --chown=root:root app/install/composer.sh /tmp
RUN /tmp/composer.sh

# Serviços
RUN apt update && apt install -y\
 supervisor\
 cron

# Configuração do nginx
COPY --chmod=644 --chown=root:root app/templates/nginx /etc/nginx/sites-enabled/default

# Limpeza do sistema
RUN apt autoremove -y && apt-get clean -y && apt autoclean -y
RUN rm -Rf /tmp/*

# Portas
EXPOSE 80

# Inicialização
COPY --chmod=744 --chown=root:root app/entrypoint/init.sh /docker-entrypoint/init.sh

ENTRYPOINT ["/docker-entrypoint/init.sh"]
