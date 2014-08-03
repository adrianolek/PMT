pwd = Dir.pwd

Vagrant.configure(2) do |config|
  config.vm.define 'base' do |machine|
    machine.vm.provider 'docker' do |d|
      d.build_dir  = 'docker/base'
      d.build_args = ['--tag', 'pmt-base']
      d.remains_running = false
    end
  end

  config.vm.define 'data' do |machine|
    machine.vm.provider 'docker' do |d|
      d.name = 'pmt.data'
      d.build_dir  = 'docker/data'
      d.build_args = ['--tag', 'pmt-data']
      d.remains_running = false
    end
  end

  config.vm.define 'mysql' do |machine|
    machine.vm.provider 'docker' do |d|
      d.name = 'pmt.mysql'
      d.build_dir  = 'docker/mysql'
      d.build_args = ['--tag', 'pmt-mysql']
      d.create_args = ['--volumes-from', 'pmt.data']
      d.volumes = %W(#{pwd}/docker/mysql/init.sql:/init.sql:/init.sql
                   #{pwd}/docker/mysql/my.cnf:/etc/mysql/my.cnf:/etc/mysql/my.cnf)
    end
  end

  config.vm.define 'php' do |machine|
    machine.vm.provider 'docker' do |d|
      d.name = 'pmt.php'
      d.build_dir  = 'docker/php'
      d.build_args = ['--tag', 'pmt-php']
      d.link('pmt.mysql:mysql')
      d.volumes = %W(#{pwd}:/var/www
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
      d.volumes = %W(#{pwd}:/var/www
                   #{pwd}/docker/nginx/vhost.conf:/etc/nginx/sites-enabled/default
                   #{pwd}/docker/nginx/nginx.conf:/etc/nginx/nginx.conf)
      d.ports = ['80:80']
    end
  end

end