pwd = Dir.pwd

Vagrant.configure(2) do |config|
  config.vm.define 'base' do |machine|
    machine.vm.provider 'docker' do |d|
      d.build_dir  = 'docker/base'
      d.build_args = ['--tag', 'pmt-base']
      d.remains_running = false
    end
  end

  config.vm.define 'mysqldata' do |machine|
    machine.vm.provider 'docker' do |d|
      d.name = 'pmt.mysqldata'
      d.image  = 'pmt-base'
      d.create_args = ['-v', '/var/lib/mysql']
      d.remains_running = false
    end
  end

  config.vm.define 'uploads' do |machine|
    machine.vm.provider 'docker' do |d|
      d.name = 'pmt.uploads'
      d.image  = 'pmt-base'
      d.create_args = ['-v', '/srv/uploads']
      d.remains_running = false
    end
  end

  config.vm.define 'mysql' do |machine|
    machine.vm.provider 'docker' do |d|
      d.name = 'pmt.mysql'
      d.build_dir  = 'docker/mysql'
      d.build_args = ['--tag', 'pmt-mysql']
      d.create_args = ['--volumes-from', 'pmt.mysqldata']
      d.volumes = %W(#{pwd}/docker/mysql/init.sql:/init.sql:/init.sql
                   #{pwd}/docker/mysql/my.cnf:/etc/mysql/my.cnf:/etc/mysql/my.cnf)
    end
  end

  config.vm.define 'php' do |machine|
    machine.vm.provider 'docker' do |d|
      d.name = 'pmt.php' if ARGV[0] != 'docker-run'
      d.create_args = ['--volumes-from', 'pmt.uploads']
      d.create_args += ['--rm', '--workdir=/var/www'] if ARGV[0] == 'docker-run'
      d.build_dir  = 'docker/php'
      d.build_args = ['--tag', 'pmt-php']
      d.link('pmt.mysql:mysql')
      d.volumes = %W(#{pwd}:/var/www
                  #{pwd}/docker/php/entrypoint.sh:/entrypoint.sh
                  #{pwd}/docker/php/php.ini:/etc/php5/fpm/php.ini
                  #{pwd}/docker/php/www.conf:/etc/php5/fpm/pool.d/www.conf)
    end
  end

  config.vm.define 'nginx' do |machine|
    machine.vm.provider 'docker' do |d|
      d.name = 'pmt.nginx'
      d.build_dir  = 'docker/nginx'
      d.build_args = ['--tag', 'pmt-nginx']
      d.link('pmt.php:php')
      d.create_args = ['--volumes-from', 'pmt.uploads']
      d.volumes = %W(#{pwd}:/var/www
                   #{pwd}/docker/nginx/entrypoint.sh:/entrypoint.sh
                   #{pwd}/docker/nginx/vhost.conf:/etc/nginx/sites-enabled/default
                   #{pwd}/docker/nginx/nginx.conf:/etc/nginx/nginx.conf)
      d.ports = ['80:80']
    end
  end

end