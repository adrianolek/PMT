#!/bin/sh

is_present() {
    local name=${1}
    local container
    container=`docker ps -a | grep -w ${PREFIX}.${name}`
    if [ "${container}" ]; then
        return 0
    fi

    return 1
}

is_running() {
    local name=${1}
    local container
    container=`docker ps | grep -w ${PREFIX}.${name}`
    if [ "${container}" ]; then
        return 0
    fi

    return 1
}

is_image() {
    local name=${1}
    local image
    image=`docker images | grep -w ${IPREFIX}-${name}`
    if [ "${image}" ]; then
        return 0
    fi

    return 1
}

docker_start() {
    IPREFIX=${FLAGS_imageprefix}
    PREFIX=${FLAGS_prefix}

    if ! is_present 'mysqldata'; then
        if [ ! -f $(pwd)/docker/mysql/init.sql ]; then
            echo >&2 "Please create $(pwd)/docker/mysql/init.sql"
            exit 1
        fi

        docker run -d -v=/var/lib/mysql --name=${PREFIX}.mysqldata busybox true
    fi

    if ! is_present 'sessions'; then
        docker run -d -v=/srv/sessions --name=${PREFIX}.sessions busybox true
        docker run --rm --volumes-from=${PREFIX}.sessions busybox chmod 733 /srv/sessions
    fi

    if ! is_present 'uploads'; then
        docker run -d -v=/srv/uploads --name=${PREFIX}.uploads busybox true
        docker run --rm --volumes-from=${PREFIX}.uploads busybox chmod 777 /srv/uploads
    fi

    if is_present 'mysql'; then
        echo "start: ${PREFIX}.mysql already running"
    elif ! is_image 'mysql'; then
        echo "image ${IPREFIX}-mysql is missing, you need to build it first"
    else
        if [ ${FLAGS_debug} -eq ${FLAGS_TRUE} ]; then
            docker run -d \
                --name=${PREFIX}.mysql \
                --publish ${FLAGS_mysqlport}:3306 \
                --restart=always \
                --volumes-from=${PREFIX}.mysqldata \
                -v $(pwd)/docker/mysql/init.sql:/init.sql \
                -v $(pwd)/docker/mysql/my.cnf:/etc/mysql/my.cnf \
                ${IPREFIX}-mysql
        else
            docker run -d \
                --name=${PREFIX}.mysql \
                --restart=always \
                --volumes-from=${PREFIX}.mysqldata \
                -v $(pwd)/docker/mysql/init.sql:/init.sql \
                -v $(pwd)/docker/mysql/my.cnf:/etc/mysql/my.cnf \
                ${IPREFIX}-mysql
        fi
    fi

    if is_present 'php'; then
        echo "start: ${PREFIX}.php already running"
    elif ! is_image 'php'; then
        echo "image ${IPREFIX}-php is missing, you need to build it first"
    else
        if [ ${FLAGS_debug} -eq ${FLAGS_TRUE} ]; then
            docker run -d \
                --name=${PREFIX}.php \
                --restart=always \
                --link ${PREFIX}.mysql:mysql \
                --volumes-from=${PREFIX}.sessions \
                --volumes-from=${PREFIX}.uploads \
                -v $(pwd)/docker/php/entrypoint.sh:/entrypoint.sh \
                -v $(pwd):/var/www \
                -v $(pwd)/docker/php/php.ini:/etc/php5/fpm/php.ini \
                -v $(pwd)/docker/php/xdebug.ini:/etc/php5/fpm/conf.d/xdebug-conf.ini \
                -v $(pwd)/docker/php/www.conf:/etc/php5/fpm/pool.d/www.conf \
                ${IPREFIX}-php
        else
            docker run -d \
                --name=${PREFIX}.php \
                --restart=always \
                --link ${PREFIX}.mysql:mysql \
                --volumes-from=${PREFIX}.sessions \
                --volumes-from=${PREFIX}.uploads \
                -v $(pwd)/docker/php/entrypoint.sh:/entrypoint.sh \
                -v $(pwd):/var/www \
                -v $(pwd)/docker/php/php.ini:/etc/php5/fpm/php.ini \
                -v /dev/null:/etc/php5/fpm/conf.d/20-xdebug.ini \
                -v $(pwd)/docker/php/www.conf:/etc/php5/fpm/pool.d/www.conf \
                ${IPREFIX}-php
        fi
    fi

    if is_present 'nginx'; then
        echo "start: ${PREFIX}.nginx already running"
    elif ! is_image 'mysql'; then
        echo "image ${IPREFIX}-nginx is missing, you need to build it first"
    else
        docker run -d \
            --name=${PREFIX}.nginx \
            --restart=always \
            --link ${PREFIX}.php:php \
            --volumes-from=${PREFIX}.uploads \
            -v $(pwd)/docker/nginx/entrypoint.sh:/entrypoint.sh \
            -v $(pwd):/var/www \
            -v $(pwd)/docker/nginx/vhost.conf:/etc/nginx/sites-enabled/default \
            -v $(pwd)/docker/nginx/nginx.conf:/etc/nginx/nginx.conf \
            -p ${FLAGS_port}:80 \
            ${IPREFIX}-nginx
    fi
}

docker_stop() {
    PREFIX=${FLAGS_prefix}

    for NAME in 'nginx' 'php' 'mysql'; do
        if is_running ${NAME}; then
            docker stop ${PREFIX}.${NAME}
            docker rm -v ${PREFIX}.${NAME}
        elif is_present ${NAME}; then
            docker rm -v ${PREFIX}.${NAME}
        else
            echo "stop: ${PREFIX}.${NAME} isn't running"
        fi
    done
}

docker_restart() {
    docker_stop
    docker_start
}

docker_php() {
    IPREFIX=${FLAGS_imageprefix}
    PREFIX=${FLAGS_prefix}

    if ! is_present 'mysql'; then
        echo "${PREFIX}.mysql container needs to be running"
        echo "Start the containers first, using command: $0 start"
        return 1
    fi

    docker run --rm -t -i \
        --link ${PREFIX}.mysql:mysql \
        --volumes-from=${PREFIX}.uploads \
        -v $(pwd)/docker/php/entrypoint.sh:/entrypoint.sh \
        -v $(pwd):/var/www \
        -v $(pwd)/docker/php/php-cli.ini:/etc/php5/cli/php.ini \
        -v $(pwd)/docker/php/xdebug.ini:/etc/php5/cli/conf.d/xdebug-config.ini \
        --env 'PHP_IDE_CONFIG=serverName=localhost' \
        -w /var/www \
        ${IPREFIX}-php \
        $@
}

docker_ant() {
    docker run --rm \
        -v $HOME/.composer/:/root/.composer \
        -v $(pwd):/var/www \
        ${FLAGS_imageprefix}-ant -logger org.apache.tools.ant.listener.AnsiColorLogger -Ddocker=1 -Duid=$(id -u) -Dgid=$(id -g) $*
}

docker_test() {
    PREFIX=${FLAGS_prefix}
    IPREFIX=${FLAGS_imageprefix}

    if [ ! -f $(pwd)/docker/mysql/init.sql ]; then
        echo >&2 "Please create $(pwd)/docker/mysql/init.sql"
        exit 1
    fi

    docker run -d -v=/var/lib/mysql --name=${PREFIX}.mysqldata busybox true

    docker run -d -v=/srv/uploads --name=${PREFIX}.uploads busybox true
    docker run --rm --volumes-from=${PREFIX}.uploads busybox chmod 777 /srv/uploads

    docker run -d \
        --name=${PREFIX}.mysql \
        --volumes-from=${PREFIX}.mysqldata \
        -v $(pwd)/docker/mysql/init.sql:/init.sql \
        -v $(pwd)/docker/mysql/my.cnf:/etc/mysql/my.cnf \
        ${IPREFIX}-mysql

    # wait for the mysql container to start
    sleep 3

    docker_php app/console doctrine:schema:create --env=test
    docker_php bin/phpunit -c app $*

    local status=$?

    docker stop ${PREFIX}.mysql
    docker rm -v ${PREFIX}.mysql
    docker rm -v ${PREFIX}.mysqldata
    docker rm -v ${PREFIX}.uploads

    return ${status}
}

docker_build_image() {
    if [ ${FLAGS_cache} -eq ${FLAGS_TRUE} ]; then
        docker build --rm -t ${FLAGS_imageprefix}-$1 docker/$1
    else
        docker build --no-cache --rm -t ${FLAGS_imageprefix}-$1 docker/$1
    fi
}

docker_build() {
    docker build --no-cache --rm -t ${FLAGS_imageprefix}-base docker/base

    for NAME in 'ant' 'mysql' 'php' 'nginx'; do
        docker_build_image ${NAME}
    done
}

docker_cleanup_images() {
    for NAME in 'nginx' 'php' 'mysql' 'ant' 'base'; do
        if is_image "${NAME}"; then
            echo "cleanup images: removing ${FLAGS_imageprefix}-${NAME}"
            docker rmi "${FLAGS_imageprefix}-${NAME}"
        fi
    done
}

docker_cleanup() {
    PREFIX=${FLAGS_prefix}

    for NAME in 'nginx' 'php' 'mysql' 'mysqldata' 'sessions' 'uploads'; do
        if is_running ${NAME}; then
            echo "cleanup: stopping ${PREFIX}.${NAME}"
            docker stop ${PREFIX}.${NAME}
        fi

        if is_present ${NAME}; then
            echo "cleanup: removing ${PREFIX}.${NAME}"
            docker rm -v ${PREFIX}.${NAME}
        fi
    done
}

set_flags () {
    FLAGS "$@" || exit $?
    eval set -- "${FLAGS_ARGV}"
}

main () {
    # source shflags
    . docker/shflags

    local command=$1

    if [ ! -z ${command} ]; then
        shift
    fi

    case ${command} in
        'start')
            DEFINE_string 'imageprefix' 'pmt' 'Docker images prefix'
            DEFINE_string 'prefix' 'pmt' 'Docker containers prefix'
            DEFINE_string 'port' '80' 'Bind to port' 'p'
            DEFINE_boolean 'debug' false 'Enable xdebug and expose mysql port' 'd'
            DEFINE_string 'mysqlport' '3306' 'Bind mysql to port' 'm'
            FLAGS_HELP="USAGE: $0 start [flags]"

            set_flags "$@"
            docker_start
            ;;

        'restart')
            DEFINE_string 'imageprefix' 'pmt' 'Docker images prefix'
            DEFINE_string 'prefix' 'pmt' 'Docker containers prefix'
            DEFINE_string 'port' '80' 'Bind to port' 'p'
            DEFINE_boolean 'debug' false 'Enable xdebug and expose mysql port' 'd'
            DEFINE_string 'mysqlport' '3306' 'Bind mysql to port' 'm'
            FLAGS_HELP="USAGE: $0 restart [flags]"

            set_flags "$@"
            docker_restart
            ;;

        'stop')
            DEFINE_string 'prefix' 'pmt' 'Docker containers prefix'
            FLAGS_HELP="USAGE: $0 stop [flags]"

            set_flags "$@"
            docker_stop
            ;;

        'build')
            DEFINE_string 'imageprefix' 'pmt' 'Docker images prefix'
            DEFINE_boolean 'cache' true 'Use cache when building'
            FLAGS_HELP="USAGE: $0 build"

            set_flags "$@"
            docker_build
            ;;

        'build-image')
            DEFINE_string 'imageprefix' 'pmt' 'Docker image prefix'
            DEFINE_boolean 'cache' true 'Use cache when building'
            FLAGS_HELP="USAGE: $0 build-image image_name"

            set_flags "$@"
            docker_build_image "$@"
            ;;

        'ant')
            DEFINE_string 'imageprefix' 'pmt' 'Docker images prefix'
            FLAGS_HELP="USAGE: $0 ant 'target'"

            set_flags "$@"
            docker_ant "$@"
            ;;

        'php')
            DEFINE_string 'imageprefix' 'pmt' 'Docker images prefix'
            DEFINE_string 'prefix' 'pmt' 'Docker containers prefix'
            FLAGS_HELP="USAGE: $0 php [flags] 'command_to_run'"

            set_flags "$@"
            docker_php "$@"
            ;;
        'cleanup')
            DEFINE_string 'prefix' 'pmt' 'Docker containers prefix'
            FLAGS_HELP="USAGE: $0 cleanup [flags]"

            set_flags "$@"
            docker_cleanup
            ;;

        'cleanup-images')
            DEFINE_string 'imageprefix' 'pmt' 'Docker images prefix'
            FLAGS_HELP="USAGE: $0 cleanup-images"

            set_flags "$@"
            docker_cleanup_images
            ;;

        'test')
            DEFINE_string 'prefix' 'pmt-test' 'Docker containers prefix'
            FLAGS_HELP="USAGE: $0 test"

            set_flags "$@"
            docker_test "$@"
            ;;

        *)
            echo "Available commands:
build\tBuilds all the docker images
build-image\tBuilds single docker image
start\tStarts the application
stop\tStops the application
restart\tRestarts the application
php\tExecutes specified command inside the php container
ant\tRuns the specified ant target
cleanup\tRemoves all the containers including persistent ones (mysql data, uploads, sessions)
cleanup-images\tRemoves all the images
test\tRuns tests"
            echo 'Use --help to learn more about specific command'
            ;;
    esac
}

main "$@"
